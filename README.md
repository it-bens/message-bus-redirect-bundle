# The Message Bus Redirect Bundle

There are some bundles that cannot handle multiple buses in one application. 
That can be a problem if commands or queries should be passed to different buses.
This bundle can redirect envelops of the Symfony messenger from one bus to another by using a middleware.

## How to install the bundle?
The package can be installed via Composer:
```bash
composer require it-bens/message-bus-redirect-bundle
```
If you're using Symfony Flex, the bundle will be automatically enabled. For older apps, enable it in your Kernel class.

## How does the redirection process work?
Redirecting an envelope from one bus to another is quite simple with a middleware. It has to catch the envelope from
the current bus and dispatch it into the target bus. Currently, the middleware does not pass the envelope 
to the next middleware. That means that the envelope will not be processed on the source bus. 
(this behavior may change or become configurable)

### How does the bundle know what the target bus is?
The target bus is determined by redirect strategies. That are services that implement the `RedirectStrategyInterface`.
They receive the envelope and return the name of the bus it should be passed to.

Currently, two strategies are implemented: the `MessageClassStrategy` and the `DecisionMakerStrategy`. 
The first one uses a Message-Class-to-Bus-Name map from the bundle configuration (described later) to find the bus name
by the class of the message. The second one is more flexible. It allows the creation of decision maker services, 
that implement the `DecisionMakerInterface`. This strategy passes the responsibility for the logic to the person,
who uses this bundle.

> ⚠ Multiple strategies can be used in a configured order. The middleware will use the first non-null bus name that is
> returned from a strategy. 

> ⚠ The `DecisionMakerStrategy` calls the decision makers in no particular order. So, the decision makers should always
> return `null` if they should be not responsible.

## How to configure this bundle?
The following configuration uses the `MessageClassStrategy` and the `DecisionMakerStrategy`.
```yaml
itb_message_bus_redirect:
  redirect_strategies:
    - ITB\MessageBusRedirectBundle\RedirectStrategies\MessageClassStrategy
    - ITB\MessageBusRedirectBundle\RedirectStrategies\DecisionMakerStrategy
```
Any unknown strategy will result in a configuration error.

The `MessageClassStrategy` requires additional configuration to work:
```yaml
  message_classes:
    - { message_class: SomeNamespace\CreateObjectCommand, bus_name: command.bus }
    - { message_class: SomeNamespace\QueryObjectsCommand, bus_name: query.bus }
```
The message classes have to be fully qualified class names. The bus names have to match the ones from the messenger configuration.

The `DecisionMakerStrategy` requires no additional configuration but at least one registered decision maker.

## How to enable the middleware?
The middleware will only be used if a bus is configured to do so:
```yaml
framework:
    messenger:
        default_bus: default.bus
        buses:
            default.bus:
                default_middleware: false
                middleware:
                    - ITB\MessageBusRedirectBundle\MessageRedirectMiddleware
                    - add_bus_name_stamp_middleware
                    - reject_redelivered_message_middleware
                    - dispatch_after_current_bus
                    - failed_message_processing_middleware
                    - send_message
```
The Symfony messenger allows no explicit middleware order. 
In general the `MessageRedirectMiddleware` can be used in any position and will work just fine (if no other middleware interferes).
But it makes more sense to place it on one of the first positions to avoid unnecessary processing. If the configured bus
should only be used for redirection, the `MessageRedirectMiddleware` should be the only middleware.
If other middleware should be called they have to be put first by their service name. 
(this example uses the default middlewares normally configured by Symfony but without the `HandleMessageMiddleware`)

## Contributing
I am really happy that the software developer community loves Open Source, like I do! ♥

That's why I appreciate every issue that is opened (preferably constructive)
and every pull request that provides other or even better code to this package.

You are all breathtaking!