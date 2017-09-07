<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4c0fa9434d3d4ce5a3553c4592c44f1f
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
            'MaxMind\\Db\\' => 11,
            'MaxMind\\' => 8,
        ),
        'G' => 
        array (
            'GeoIp2\\' => 7,
        ),
        'C' => 
        array (
            'Composer\\CaBundle\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'MaxMind\\Db\\' => 
        array (
            0 => __DIR__ . '/..' . '/maxmind-db/reader/src/MaxMind/Db',
        ),
        'MaxMind\\' => 
        array (
            0 => __DIR__ . '/..' . '/maxmind/web-service-common/src',
        ),
        'GeoIp2\\' => 
        array (
            0 => __DIR__ . '/..' . '/geoip2/geoip2/src',
        ),
        'Composer\\CaBundle\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/ca-bundle/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'H' => 
        array (
            'Httpful' => 
            array (
                0 => __DIR__ . '/..' . '/nategood/httpful/src',
            ),
        ),
    );

    public static $classMap = array (
        'Aelia\\WC\\Admin_Message' => __DIR__ . '/../..' . '/lib/classes/base/messages/admin_message.php',
        'Aelia\\WC\\AeliaSimpleXMLElement' => __DIR__ . '/../..' . '/lib/classes/base/xml/aelia-simplexmlelement.php',
        'Aelia\\WC\\Aelia_Install' => __DIR__ . '/../..' . '/lib/classes/base/install/install.php',
        'Aelia\\WC\\Aelia_Integration' => __DIR__ . '/../..' . '/lib/classes/base/integration/aelia-integration.php',
        'Aelia\\WC\\Aelia_Plugin' => __DIR__ . '/../..' . '/lib/classes/base/plugin/aelia-plugin.php',
        'Aelia\\WC\\Aelia_SessionManager' => __DIR__ . '/../..' . '/lib/classes/base/session/aelia-session-manager.php',
        'Aelia\\WC\\Base_Class' => __DIR__ . '/../..' . '/lib/classes/base/base-classes/aelia-base-class.php',
        'Aelia\\WC\\Definitions' => __DIR__ . '/../..' . '/lib/classes/definitions/definitions.php',
        'Aelia\\WC\\ExchangeRatesModel' => __DIR__ . '/../..' . '/lib/classes/currency/aelia-wc-exchangeratesmodel.php',
        'Aelia\\WC\\Free_Plugin_Updater' => __DIR__ . '/../..' . '/lib/classes/updater/free-plugin-updater.php',
        'Aelia\\WC\\IAelia_Plugin' => __DIR__ . '/../..' . '/lib/classes/base/plugin/aelia-plugin.php',
        'Aelia\\WC\\IExchangeRatesModel' => __DIR__ . '/../..' . '/lib/classes/currency/aelia-wc-exchangeratesmodel.php',
        'Aelia\\WC\\IP2Location' => __DIR__ . '/../..' . '/lib/classes/ip2location/ip2location.php',
        'Aelia\\WC\\Logger' => __DIR__ . '/../..' . '/lib/classes/logger/logger.php',
        'Aelia\\WC\\Message' => __DIR__ . '/../..' . '/lib/classes/base/messages/message.php',
        'Aelia\\WC\\Messages' => __DIR__ . '/../..' . '/lib/classes/base/messages/messages.php',
        'Aelia\\WC\\Model' => __DIR__ . '/../..' . '/lib/classes/base/model/model.php',
        'Aelia\\WC\\NotImplementedException' => __DIR__ . '/../..' . '/lib/classes/base/exceptions/aelia-exceptions.php',
        'Aelia\\WC\\Order' => __DIR__ . '/../..' . '/lib/classes/base/orders/order.php',
        'Aelia\\WC\\Premium_Plugin_Updater' => __DIR__ . '/../..' . '/lib/classes/updater/premium-plugin-updater.php',
        'Aelia\\WC\\Semaphore' => __DIR__ . '/../..' . '/lib/classes/base/semaphore/semaphore.php',
        'Aelia\\WC\\Settings' => __DIR__ . '/../..' . '/lib/classes/base/settings/settings.php',
        'Aelia\\WC\\Settings_Renderer' => __DIR__ . '/../..' . '/lib/classes/base/settings/settings-renderer.php',
        'Aelia\\WC\\Updater' => __DIR__ . '/../..' . '/lib/classes/updater/updater.php',
        'Aelia\\WC\\WC_AeliaFoundationClasses_Install' => __DIR__ . '/../..' . '/lib/classes/install/aelia-wc-afc-install.php',
        'Aelia_WC_AFC_RequirementsChecks' => __DIR__ . '/../..' . '/lib/classes/install/aelia-wc-afc-requirementscheck.php',
        'Aelia_WC_RequirementsChecks' => __DIR__ . '/../..' . '/lib/classes/install/aelia-wc-requirementscheck.php',
        'Composer\\CaBundle\\CaBundle' => __DIR__ . '/..' . '/composer/ca-bundle/src/CaBundle.php',
        'GeoIp2\\Compat\\JsonSerializable' => __DIR__ . '/..' . '/geoip2/geoip2/src/Compat/JsonSerializable.php',
        'GeoIp2\\Database\\Reader' => __DIR__ . '/..' . '/geoip2/geoip2/src/Database/Reader.php',
        'GeoIp2\\Exception\\AddressNotFoundException' => __DIR__ . '/..' . '/geoip2/geoip2/src/Exception/AddressNotFoundException.php',
        'GeoIp2\\Exception\\AuthenticationException' => __DIR__ . '/..' . '/geoip2/geoip2/src/Exception/AuthenticationException.php',
        'GeoIp2\\Exception\\GeoIp2Exception' => __DIR__ . '/..' . '/geoip2/geoip2/src/Exception/GeoIp2Exception.php',
        'GeoIp2\\Exception\\HttpException' => __DIR__ . '/..' . '/geoip2/geoip2/src/Exception/HttpException.php',
        'GeoIp2\\Exception\\InvalidRequestException' => __DIR__ . '/..' . '/geoip2/geoip2/src/Exception/InvalidRequestException.php',
        'GeoIp2\\Exception\\OutOfQueriesException' => __DIR__ . '/..' . '/geoip2/geoip2/src/Exception/OutOfQueriesException.php',
        'GeoIp2\\Model\\AbstractModel' => __DIR__ . '/..' . '/geoip2/geoip2/src/Model/AbstractModel.php',
        'GeoIp2\\Model\\AnonymousIp' => __DIR__ . '/..' . '/geoip2/geoip2/src/Model/AnonymousIp.php',
        'GeoIp2\\Model\\Asn' => __DIR__ . '/..' . '/geoip2/geoip2/src/Model/Asn.php',
        'GeoIp2\\Model\\City' => __DIR__ . '/..' . '/geoip2/geoip2/src/Model/City.php',
        'GeoIp2\\Model\\ConnectionType' => __DIR__ . '/..' . '/geoip2/geoip2/src/Model/ConnectionType.php',
        'GeoIp2\\Model\\Country' => __DIR__ . '/..' . '/geoip2/geoip2/src/Model/Country.php',
        'GeoIp2\\Model\\Domain' => __DIR__ . '/..' . '/geoip2/geoip2/src/Model/Domain.php',
        'GeoIp2\\Model\\Enterprise' => __DIR__ . '/..' . '/geoip2/geoip2/src/Model/Enterprise.php',
        'GeoIp2\\Model\\Insights' => __DIR__ . '/..' . '/geoip2/geoip2/src/Model/Insights.php',
        'GeoIp2\\Model\\Isp' => __DIR__ . '/..' . '/geoip2/geoip2/src/Model/Isp.php',
        'GeoIp2\\ProviderInterface' => __DIR__ . '/..' . '/geoip2/geoip2/src/ProviderInterface.php',
        'GeoIp2\\Record\\AbstractPlaceRecord' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/AbstractPlaceRecord.php',
        'GeoIp2\\Record\\AbstractRecord' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/AbstractRecord.php',
        'GeoIp2\\Record\\City' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/City.php',
        'GeoIp2\\Record\\Continent' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/Continent.php',
        'GeoIp2\\Record\\Country' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/Country.php',
        'GeoIp2\\Record\\Location' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/Location.php',
        'GeoIp2\\Record\\MaxMind' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/MaxMind.php',
        'GeoIp2\\Record\\Postal' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/Postal.php',
        'GeoIp2\\Record\\RepresentedCountry' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/RepresentedCountry.php',
        'GeoIp2\\Record\\Subdivision' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/Subdivision.php',
        'GeoIp2\\Record\\Traits' => __DIR__ . '/..' . '/geoip2/geoip2/src/Record/Traits.php',
        'GeoIp2\\WebService\\Client' => __DIR__ . '/..' . '/geoip2/geoip2/src/WebService/Client.php',
        'Httpful\\Bootstrap' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Bootstrap.php',
        'Httpful\\Exception\\ConnectionErrorException' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Exception/ConnectionErrorException.php',
        'Httpful\\Handlers\\CsvHandler' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Handlers/CsvHandler.php',
        'Httpful\\Handlers\\FormHandler' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Handlers/FormHandler.php',
        'Httpful\\Handlers\\JsonHandler' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Handlers/JsonHandler.php',
        'Httpful\\Handlers\\MimeHandlerAdapter' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Handlers/MimeHandlerAdapter.php',
        'Httpful\\Handlers\\XHtmlHandler' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Handlers/XHtmlHandler.php',
        'Httpful\\Handlers\\XmlHandler' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Handlers/XmlHandler.php',
        'Httpful\\Http' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Http.php',
        'Httpful\\Httpful' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Httpful.php',
        'Httpful\\Mime' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Mime.php',
        'Httpful\\Proxy' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Proxy.php',
        'Httpful\\Request' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Request.php',
        'Httpful\\Response' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Response.php',
        'Httpful\\Response\\Headers' => __DIR__ . '/..' . '/nategood/httpful/src/Httpful/Response/Headers.php',
        'MaxMind\\Db\\Reader' => __DIR__ . '/..' . '/maxmind-db/reader/src/MaxMind/Db/Reader.php',
        'MaxMind\\Db\\Reader\\Decoder' => __DIR__ . '/..' . '/maxmind-db/reader/src/MaxMind/Db/Reader/Decoder.php',
        'MaxMind\\Db\\Reader\\InvalidDatabaseException' => __DIR__ . '/..' . '/maxmind-db/reader/src/MaxMind/Db/Reader/InvalidDatabaseException.php',
        'MaxMind\\Db\\Reader\\Metadata' => __DIR__ . '/..' . '/maxmind-db/reader/src/MaxMind/Db/Reader/Metadata.php',
        'MaxMind\\Db\\Reader\\Util' => __DIR__ . '/..' . '/maxmind-db/reader/src/MaxMind/Db/Reader/Util.php',
        'MaxMind\\Exception\\AuthenticationException' => __DIR__ . '/..' . '/maxmind/web-service-common/src/Exception/AuthenticationException.php',
        'MaxMind\\Exception\\HttpException' => __DIR__ . '/..' . '/maxmind/web-service-common/src/Exception/HttpException.php',
        'MaxMind\\Exception\\InsufficientFundsException' => __DIR__ . '/..' . '/maxmind/web-service-common/src/Exception/InsufficientFundsException.php',
        'MaxMind\\Exception\\InvalidInputException' => __DIR__ . '/..' . '/maxmind/web-service-common/src/Exception/InvalidInputException.php',
        'MaxMind\\Exception\\InvalidRequestException' => __DIR__ . '/..' . '/maxmind/web-service-common/src/Exception/InvalidRequestException.php',
        'MaxMind\\Exception\\IpAddressNotFoundException' => __DIR__ . '/..' . '/maxmind/web-service-common/src/Exception/IpAddressNotFoundException.php',
        'MaxMind\\Exception\\PermissionRequiredException' => __DIR__ . '/..' . '/maxmind/web-service-common/src/Exception/PermissionRequiredException.php',
        'MaxMind\\Exception\\WebServiceException' => __DIR__ . '/..' . '/maxmind/web-service-common/src/Exception/WebServiceException.php',
        'MaxMind\\WebService\\Client' => __DIR__ . '/..' . '/maxmind/web-service-common/src/WebService/Client.php',
        'MaxMind\\WebService\\Http\\CurlRequest' => __DIR__ . '/..' . '/maxmind/web-service-common/src/WebService/Http/CurlRequest.php',
        'MaxMind\\WebService\\Http\\Request' => __DIR__ . '/..' . '/maxmind/web-service-common/src/WebService/Http/Request.php',
        'MaxMind\\WebService\\Http\\RequestFactory' => __DIR__ . '/..' . '/maxmind/web-service-common/src/WebService/Http/RequestFactory.php',
        'Monolog\\ErrorHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/ErrorHandler.php',
        'Monolog\\Formatter\\ChromePHPFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/ChromePHPFormatter.php',
        'Monolog\\Formatter\\ElasticaFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/ElasticaFormatter.php',
        'Monolog\\Formatter\\FlowdockFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/FlowdockFormatter.php',
        'Monolog\\Formatter\\FluentdFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/FluentdFormatter.php',
        'Monolog\\Formatter\\FormatterInterface' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/FormatterInterface.php',
        'Monolog\\Formatter\\GelfMessageFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/GelfMessageFormatter.php',
        'Monolog\\Formatter\\HtmlFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/HtmlFormatter.php',
        'Monolog\\Formatter\\JsonFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/JsonFormatter.php',
        'Monolog\\Formatter\\LineFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/LineFormatter.php',
        'Monolog\\Formatter\\LogglyFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/LogglyFormatter.php',
        'Monolog\\Formatter\\LogstashFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/LogstashFormatter.php',
        'Monolog\\Formatter\\MongoDBFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/MongoDBFormatter.php',
        'Monolog\\Formatter\\NormalizerFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/NormalizerFormatter.php',
        'Monolog\\Formatter\\ScalarFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/ScalarFormatter.php',
        'Monolog\\Formatter\\WildfireFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/WildfireFormatter.php',
        'Monolog\\Handler\\AbstractHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AbstractHandler.php',
        'Monolog\\Handler\\AbstractProcessingHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AbstractProcessingHandler.php',
        'Monolog\\Handler\\AbstractSyslogHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AbstractSyslogHandler.php',
        'Monolog\\Handler\\AmqpHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AmqpHandler.php',
        'Monolog\\Handler\\BrowserConsoleHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/BrowserConsoleHandler.php',
        'Monolog\\Handler\\BufferHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/BufferHandler.php',
        'Monolog\\Handler\\ChromePHPHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ChromePHPHandler.php',
        'Monolog\\Handler\\CouchDBHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/CouchDBHandler.php',
        'Monolog\\Handler\\CubeHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/CubeHandler.php',
        'Monolog\\Handler\\Curl\\Util' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/Curl/Util.php',
        'Monolog\\Handler\\DeduplicationHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/DeduplicationHandler.php',
        'Monolog\\Handler\\DoctrineCouchDBHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/DoctrineCouchDBHandler.php',
        'Monolog\\Handler\\DynamoDbHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/DynamoDbHandler.php',
        'Monolog\\Handler\\ElasticSearchHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php',
        'Monolog\\Handler\\ErrorLogHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ErrorLogHandler.php',
        'Monolog\\Handler\\FilterHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FilterHandler.php',
        'Monolog\\Handler\\FingersCrossedHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossedHandler.php',
        'Monolog\\Handler\\FingersCrossed\\ActivationStrategyInterface' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossed/ActivationStrategyInterface.php',
        'Monolog\\Handler\\FingersCrossed\\ChannelLevelActivationStrategy' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossed/ChannelLevelActivationStrategy.php',
        'Monolog\\Handler\\FingersCrossed\\ErrorLevelActivationStrategy' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossed/ErrorLevelActivationStrategy.php',
        'Monolog\\Handler\\FirePHPHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FirePHPHandler.php',
        'Monolog\\Handler\\FleepHookHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FleepHookHandler.php',
        'Monolog\\Handler\\FlowdockHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FlowdockHandler.php',
        'Monolog\\Handler\\GelfHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/GelfHandler.php',
        'Monolog\\Handler\\GroupHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/GroupHandler.php',
        'Monolog\\Handler\\HandlerInterface' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/HandlerInterface.php',
        'Monolog\\Handler\\HandlerWrapper' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/HandlerWrapper.php',
        'Monolog\\Handler\\HipChatHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/HipChatHandler.php',
        'Monolog\\Handler\\IFTTTHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/IFTTTHandler.php',
        'Monolog\\Handler\\LogEntriesHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/LogEntriesHandler.php',
        'Monolog\\Handler\\LogglyHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/LogglyHandler.php',
        'Monolog\\Handler\\MailHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MailHandler.php',
        'Monolog\\Handler\\MandrillHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MandrillHandler.php',
        'Monolog\\Handler\\MissingExtensionException' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MissingExtensionException.php',
        'Monolog\\Handler\\MongoDBHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MongoDBHandler.php',
        'Monolog\\Handler\\NativeMailerHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/NativeMailerHandler.php',
        'Monolog\\Handler\\NewRelicHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/NewRelicHandler.php',
        'Monolog\\Handler\\NullHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/NullHandler.php',
        'Monolog\\Handler\\PHPConsoleHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/PHPConsoleHandler.php',
        'Monolog\\Handler\\PsrHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/PsrHandler.php',
        'Monolog\\Handler\\PushoverHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/PushoverHandler.php',
        'Monolog\\Handler\\RavenHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RavenHandler.php',
        'Monolog\\Handler\\RedisHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RedisHandler.php',
        'Monolog\\Handler\\RollbarHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RollbarHandler.php',
        'Monolog\\Handler\\RotatingFileHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RotatingFileHandler.php',
        'Monolog\\Handler\\SamplingHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SamplingHandler.php',
        'Monolog\\Handler\\SlackHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SlackHandler.php',
        'Monolog\\Handler\\SlackWebhookHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SlackWebhookHandler.php',
        'Monolog\\Handler\\Slack\\SlackRecord' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/Slack/SlackRecord.php',
        'Monolog\\Handler\\SlackbotHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SlackbotHandler.php',
        'Monolog\\Handler\\SocketHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SocketHandler.php',
        'Monolog\\Handler\\StreamHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/StreamHandler.php',
        'Monolog\\Handler\\SwiftMailerHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SwiftMailerHandler.php',
        'Monolog\\Handler\\SyslogHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SyslogHandler.php',
        'Monolog\\Handler\\SyslogUdpHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SyslogUdpHandler.php',
        'Monolog\\Handler\\SyslogUdp\\UdpSocket' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SyslogUdp/UdpSocket.php',
        'Monolog\\Handler\\TestHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/TestHandler.php',
        'Monolog\\Handler\\WhatFailureGroupHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/WhatFailureGroupHandler.php',
        'Monolog\\Handler\\ZendMonitorHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ZendMonitorHandler.php',
        'Monolog\\Logger' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Logger.php',
        'Monolog\\Processor\\GitProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/GitProcessor.php',
        'Monolog\\Processor\\IntrospectionProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/IntrospectionProcessor.php',
        'Monolog\\Processor\\MemoryPeakUsageProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MemoryPeakUsageProcessor.php',
        'Monolog\\Processor\\MemoryProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MemoryProcessor.php',
        'Monolog\\Processor\\MemoryUsageProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MemoryUsageProcessor.php',
        'Monolog\\Processor\\MercurialProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MercurialProcessor.php',
        'Monolog\\Processor\\ProcessIdProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/ProcessIdProcessor.php',
        'Monolog\\Processor\\PsrLogMessageProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/PsrLogMessageProcessor.php',
        'Monolog\\Processor\\TagProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/TagProcessor.php',
        'Monolog\\Processor\\UidProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/UidProcessor.php',
        'Monolog\\Processor\\WebProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/WebProcessor.php',
        'Monolog\\Registry' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Registry.php',
        'Psr\\Log\\AbstractLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/AbstractLogger.php',
        'Psr\\Log\\InvalidArgumentException' => __DIR__ . '/..' . '/psr/log/Psr/Log/InvalidArgumentException.php',
        'Psr\\Log\\LogLevel' => __DIR__ . '/..' . '/psr/log/Psr/Log/LogLevel.php',
        'Psr\\Log\\LoggerAwareInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareInterface.php',
        'Psr\\Log\\LoggerAwareTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareTrait.php',
        'Psr\\Log\\LoggerInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerInterface.php',
        'Psr\\Log\\LoggerTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerTrait.php',
        'Psr\\Log\\NullLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/NullLogger.php',
        'Psr\\Log\\Test\\DummyTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
        'Psr\\Log\\Test\\LoggerInterfaceTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4c0fa9434d3d4ce5a3553c4592c44f1f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4c0fa9434d3d4ce5a3553c4592c44f1f::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit4c0fa9434d3d4ce5a3553c4592c44f1f::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit4c0fa9434d3d4ce5a3553c4592c44f1f::$classMap;

        }, null, ClassLoader::class);
    }
}