<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'App\\Helpers\\ApplicationHelper' => $baseDir . '/app/helpers/ApplicationHelper.php',
    'App\\Http\\Controllers\\ApplicationController' => $baseDir . '/app/http/controllers/ApplicationController.php',
    'App\\Http\\Controllers\\Home' => $baseDir . '/app/http/controllers/Home.php',
    'App\\Http\\Controllers\\Users' => $baseDir . '/app/http/controllers/Users.php',
    'App\\Models\\ApplicationRecord' => $baseDir . '/app/models/ApplicationRecord.php',
    'App\\Models\\User' => $baseDir . '/app/models/User.php',
    'Config\\Environments\\Environment' => $baseDir . '/config/environments/Environment.php',
    'Db\\Migration\\CreateUser' => $baseDir . '/db/migrations/CreateUser.php',
    'Dream\\Auth\\Auth' => $baseDir . '/src/auth/Auth.php',
    'Dream\\Auth\\Providers\\ActiveRecord' => $baseDir . '/src/auth/providers/ActiveRecord.php',
    'Dream\\Auth\\Providers\\Remember' => $baseDir . '/src/auth/providers/Remember.php',
    'Dream\\Auth\\Storage\\SessionStorage' => $baseDir . '/src/auth/storage/SessionStorage.php',
    'Dream\\Batch\\Images\\Resize' => $baseDir . '/src/batch/images/Resize.php',
    'Dream\\Container\\Container' => $baseDir . '/src/container/Container.php',
    'Dream\\Container\\ContainerException' => $baseDir . '/src/container/ContainerException.php',
    'Dream\\Container\\NotFoundException' => $baseDir . '/src/container/NotFoundException.php',
    'Dream\\Database\\ActiveRecord\\Concerns\\Relationship' => $baseDir . '/src/database/activerecord/concerns/Relationship.php',
    'Dream\\Database\\ActiveRecord\\Model' => $baseDir . '/src/database/activerecord/Model.php',
    'Dream\\Database\\ActiveRecord\\Proxy' => $baseDir . '/src/database/activerecord/Proxy.php',
    'Dream\\Database\\ActiveRecord\\RowSet' => $baseDir . '/src/database/activerecord/RowSet.php',
    'Dream\\Database\\Database' => $baseDir . '/src/database/Database.php',
    'Dream\\Database\\Migration\\Base' => $baseDir . '/src/database/migration/Base.php',
    'Dream\\Database\\Migration\\Column' => $baseDir . '/src/database/migration/Column.php',
    'Dream\\Database\\Migration\\Table' => $baseDir . '/src/database/migration/Table.php',
    'Dream\\Errors\\Error' => $baseDir . '/src/errors/Error.php',
    'Dream\\Exceptions\\UndefinedHookException' => $baseDir . '/src/exceptions/UndefinedHookException.php',
    'Dream\\Flush\\FlushMessage' => $baseDir . '/src/flush/FlushMessage.php',
    'Dream\\Foundation\\Users\\Auth\\Authenticable' => $baseDir . '/src/foundation/users/auth/Authenticable.php',
    'Dream\\Foundation\\Users\\Auth\\PasswordResetable' => $baseDir . '/src/foundation/users/auth/PasswordResetable.php',
    'Dream\\Foundation\\Users\\Auth\\Registerable' => $baseDir . '/src/foundation/users/auth/Registerable.php',
    'Dream\\Http\\Client' => $baseDir . '/src/http/Client.php',
    'Dream\\Http\\Concern\\UriFilter' => $baseDir . '/src/http/concerns/UriFilter.php',
    'Dream\\Http\\Constants' => $baseDir . '/src/http/Constants.php',
    'Dream\\Http\\Controllers\\Concerns\\Forgery' => $baseDir . '/src/http/controllers/concerns/Forgery.php',
    'Dream\\Http\\Controllers\\Controller' => $baseDir . '/src/http/controllers/Controller.php',
    'Dream\\Http\\Factory\\Kernel' => $baseDir . '/src/http/factory/Kernel.php',
    'Dream\\Http\\Factory\\ServerRequest' => $baseDir . '/src/http/factory/ServerRequest.php',
    'Dream\\Http\\Flush' => $baseDir . '/src/http/Flush.php',
    'Dream\\Http\\Message' => $baseDir . '/src/http/Message.php',
    'Dream\\Http\\Middleware\\BackLink' => $baseDir . '/src/http/middleware/BackLink.php',
    'Dream\\Http\\Middleware\\Routing' => $baseDir . '/src/http/middleware/Routing.php',
    'Dream\\Http\\Params' => $baseDir . '/src/http/Params.php',
    'Dream\\Http\\Request' => $baseDir . '/src/http/Request.php',
    'Dream\\Http\\RequestHandler' => $baseDir . '/src/http/RequestHandler.php',
    'Dream\\Http\\Response' => $baseDir . '/src/http/Response.php',
    'Dream\\Http\\ServerRequest' => $baseDir . '/src/http/ServerRequest.php',
    'Dream\\Http\\Sessions\\Cookie' => $baseDir . '/src/http/sessions/Cookie.php',
    'Dream\\Http\\Sessions\\Session' => $baseDir . '/src/http/sessions/Session.php',
    'Dream\\Http\\Stream' => $baseDir . '/src/http/Stream.php',
    'Dream\\Http\\TextStream' => $baseDir . '/src/http/TextStream.php',
    'Dream\\Http\\UploadedFile' => $baseDir . '/src/http/UploadedFile.php',
    'Dream\\Http\\Uri' => $baseDir . '/src/http/Uri.php',
    'Dream\\Kernel\\App' => $baseDir . '/src/kernel/App.php',
    'Dream\\Kernel\\Config' => $baseDir . '/src/kernel/Config.php',
    'Dream\\Kernel\\Registry' => $baseDir . '/src/kernel/Registry.php',
    'Dream\\Mail\\Mailer' => $baseDir . '/src/mail/Mailer.php',
    'Dream\\Patterns\\Factory\\HelperFactory' => $baseDir . '/src/patterns/factory/HelperFactory.php',
    'Dream\\Patterns\\Observer\\Event' => $baseDir . '/src/patterns/observer/Event.php',
    'Dream\\Route\\Dispatcher' => $baseDir . '/src/route/Dispatcher.php',
    'Dream\\Route\\Route' => $baseDir . '/src/route/Route.php',
    'Dream\\Route\\Router' => $baseDir . '/src/route/Router.php',
    'Dream\\Session\\Cookie' => $baseDir . '/src/session/Cookie.php',
    'Dream\\Session\\Session' => $baseDir . '/src/session/Session.php',
    'Dream\\Standards\\Auth\\AuthInterface' => $baseDir . '/src/standards/Auth/AuthInterface.php',
    'Dream\\Standards\\Auth\\AuthServiceInterface' => $baseDir . '/src/standards/Auth/AuthServiceInterface.php',
    'Dream\\Standards\\Auth\\StorageInterface' => $baseDir . '/src/standards/Auth/StorageInterface.php',
    'Dream\\Uploader' => $baseDir . '/src/Uploader.php',
    'Dream\\Validator' => $baseDir . '/src/Validator.php',
    'Dream\\Views\\Helpers\\Helper' => $baseDir . '/src/views/helpers/Helper.php',
    'Dream\\Views\\View' => $baseDir . '/src/views/View.php',
    'Lead\\Components\\Addition' => $baseDir . '/src/lead/components/Addition.php',
    'Lead\\Components\\Assignment' => $baseDir . '/src/lead/components/Assignment.php',
    'Lead\\Components\\BinaryOperator' => $baseDir . '/src/lead/components/BinaryOperator.php',
    'Lead\\Components\\Block' => $baseDir . '/src/lead/components/Block.php',
    'Lead\\Components\\Call' => $baseDir . '/src/lead/components/Call.php',
    'Lead\\Components\\Comparison' => $baseDir . '/src/lead/components/Comparison.php',
    'Lead\\Components\\Condition' => $baseDir . '/src/lead/components/Condition.php',
    'Lead\\Components\\Decimal' => $baseDir . '/src/lead/components/Decimal.php',
    'Lead\\Components\\Division' => $baseDir . '/src/lead/components/Division.php',
    'Lead\\Components\\Each' => $baseDir . '/src/lead/components/Each.php',
    'Lead\\Components\\EqualCompare' => $baseDir . '/src/lead/components/EqualCompare.php',
    'Lead\\Components\\GreaterThanCompare' => $baseDir . '/src/lead/components/GreaterThanCompare.php',
    'Lead\\Components\\GreaterThanOrEqual' => $baseDir . '/src/lead/components/GreaterThanOrEqual.php',
    'Lead\\Components\\Html' => $baseDir . '/src/lead/components/Html.php',
    'Lead\\Components\\IVariable' => $baseDir . '/src/lead/components/IVariable.php',
    'Lead\\Components\\Integer' => $baseDir . '/src/lead/components/Integer.php',
    'Lead\\Components\\LEcho' => $baseDir . '/src/lead/components/LEcho.php',
    'Lead\\Components\\LList' => $baseDir . '/src/lead/components/LList.php',
    'Lead\\Components\\LNull' => $baseDir . '/src/lead/components/LNull.php',
    'Lead\\Components\\LPrint' => $baseDir . '/src/lead/components/LPrint.php',
    'Lead\\Components\\LString' => $baseDir . '/src/lead/components/LString.php',
    'Lead\\Components\\LessThanCompare' => $baseDir . '/src/lead/components/LessThanCompare.php',
    'Lead\\Components\\LessThanOrEqual' => $baseDir . '/src/lead/components/LessThanOrEqual.php',
    'Lead\\Components\\LogicalAnd' => $baseDir . '/src/lead/components/LogicalAnd.php',
    'Lead\\Components\\LogicalNot' => $baseDir . '/src/lead/components/LogicalNot.php',
    'Lead\\Components\\LogicalNotEqual' => $baseDir . '/src/lead/components/LogicalNotEqual.php',
    'Lead\\Components\\LogicalOr' => $baseDir . '/src/lead/components/LogicalOr.php',
    'Lead\\Components\\Multiplication' => $baseDir . '/src/lead/components/Multiplication.php',
    'Lead\\Components\\Number' => $baseDir . '/src/lead/components/Number.php',
    'Lead\\Components\\Operator' => $baseDir . '/src/lead/components/Operator.php',
    'Lead\\Components\\PropertyFetch' => $baseDir . '/src/lead/components/PropertyFetch.php',
    'Lead\\Components\\Subtraction' => $baseDir . '/src/lead/components/Subtraction.php',
    'Lead\\Components\\Variable' => $baseDir . '/src/lead/components/Variable.php',
    'Lead\\Evaluator' => $baseDir . '/src/lead/Evaluator.php',
    'Lead\\Exceptions\\InvalidArgDefinationException' => $baseDir . '/src/lead/exceptions/InvalidArgDefinationException.php',
    'Lead\\Exceptions\\UndeclaredVariableException' => $baseDir . '/src/lead/exceptions/UndeclaredVariableException.php',
    'Lead\\Exceptions\\UndefinedMethodException' => $baseDir . '/src/lead/exceptions/UndefinedMethodException.php',
    'Lead\\IExpression' => $baseDir . '/src/lead/IExpression.php',
    'Lead\\Lexer' => $baseDir . '/src/lead/Lexer.php',
    'Lead\\Parser' => $baseDir . '/src/lead/Parser.php',
    'Lead\\Stream' => $baseDir . '/src/lead/Stream.php',
    'Lead\\Variables' => $baseDir . '/src/lead/Variables.php',
    'Psr\\Container\\ContainerExceptionInterface' => $vendorDir . '/psr/container/src/ContainerExceptionInterface.php',
    'Psr\\Container\\ContainerInterface' => $vendorDir . '/psr/container/src/ContainerInterface.php',
    'Psr\\Container\\NotFoundExceptionInterface' => $vendorDir . '/psr/container/src/NotFoundExceptionInterface.php',
    'Psr\\Http\\Client\\ClientExceptionInterface' => $vendorDir . '/psr/http-client/src/ClientExceptionInterface.php',
    'Psr\\Http\\Client\\ClientInterface' => $vendorDir . '/psr/http-client/src/ClientInterface.php',
    'Psr\\Http\\Client\\NetworkExceptionInterface' => $vendorDir . '/psr/http-client/src/NetworkExceptionInterface.php',
    'Psr\\Http\\Client\\RequestExceptionInterface' => $vendorDir . '/psr/http-client/src/RequestExceptionInterface.php',
    'Psr\\Http\\Message\\MessageInterface' => $vendorDir . '/psr/http-message/src/MessageInterface.php',
    'Psr\\Http\\Message\\RequestFactoryInterface' => $vendorDir . '/psr/http-factory/src/RequestFactoryInterface.php',
    'Psr\\Http\\Message\\RequestInterface' => $vendorDir . '/psr/http-message/src/RequestInterface.php',
    'Psr\\Http\\Message\\ResponseFactoryInterface' => $vendorDir . '/psr/http-factory/src/ResponseFactoryInterface.php',
    'Psr\\Http\\Message\\ResponseInterface' => $vendorDir . '/psr/http-message/src/ResponseInterface.php',
    'Psr\\Http\\Message\\ServerRequestFactoryInterface' => $vendorDir . '/psr/http-factory/src/ServerRequestFactoryInterface.php',
    'Psr\\Http\\Message\\ServerRequestInterface' => $vendorDir . '/psr/http-message/src/ServerRequestInterface.php',
    'Psr\\Http\\Message\\StreamFactoryInterface' => $vendorDir . '/psr/http-factory/src/StreamFactoryInterface.php',
    'Psr\\Http\\Message\\StreamInterface' => $vendorDir . '/psr/http-message/src/StreamInterface.php',
    'Psr\\Http\\Message\\UploadedFileFactoryInterface' => $vendorDir . '/psr/http-factory/src/UploadedFileFactoryInterface.php',
    'Psr\\Http\\Message\\UploadedFileInterface' => $vendorDir . '/psr/http-message/src/UploadedFileInterface.php',
    'Psr\\Http\\Message\\UriFactoryInterface' => $vendorDir . '/psr/http-factory/src/UriFactoryInterface.php',
    'Psr\\Http\\Message\\UriInterface' => $vendorDir . '/psr/http-message/src/UriInterface.php',
    'Psr\\Http\\Server\\MiddlewareInterface' => $vendorDir . '/psr/http-server-middleware/src/MiddlewareInterface.php',
    'Psr\\Http\\Server\\RequestHandlerInterface' => $vendorDir . '/psr/http-server-handler/src/RequestHandlerInterface.php',
);
