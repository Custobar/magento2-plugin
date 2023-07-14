# Explanation of the Laminas and Zend client usages
During the work to support 2.4.6 version of Magento, it was agreed that the module should also support
2.4.4 since at the time of writing (June 2023) that's the oldest officially supported version of Magento.
But there was one little change in 2.4.6: `Magento\Framework\HTTP\LaminasClient` was introduced and it replaces
`Magento\Framework\HTTP\ZendClient`, while latter is completely deprecated and unusable. So now the module needs to
work with 2.4.4 where ZendClient is available and LaminasClient is not and also 2.4.6 where ZendClient
is unusable and LaminasClient is available. And as far as it was seen, these are not wrapped
under any interface that could be used regardless of the version.

The solution here is that the `Model/CustobarApi/ClientBuilder` implementation is refactored to not
create any actual client instance directly, but instead it resolves the client type we want to use
based on the system version and only after that it creates the client. So what happens in the class
when `buildClient()` is called:

1. `vendor/magento/framework/App/ProductMetadataInterface` is used to get the system version.
2. Array of `Model/CustobarApi/ClientBuilder/VersionClientProviderResolverInterface` is looped through
   and we call the method `doesVersionApply()` on each instance with the system version to figure out which
   instance should we use.
3. When a match is found, `getProvider() `is called on the resolver instance to retrieve an instance
   of `Model/CustobarApi/ClientBuilder/VersionClientProviderInterface` which is meant to handle the
   actual creation of the client.
    1. In pre 2.4.6 versions the provider class should be `Model/CustobarApi/ClientBuilder/VersionClientProvider/ZendClient`
    2. In 2.4.6 and newer the provider class should be `Model/CustobarApi/ClientBuilder/VersionClientProvider/LaminasClient`
4. The implementations of `Model/CustobarApi/ClientBuilder/VersionClientProviderInterface` intentionally
   use `ObjectManager` to instantiate the client, as otherwise code compilation and other dependency related
   logic will throw errors due to missing class.
5. Additionally the instantiated client is passed to an instance of `Model/CustobarApi/ClientInterface`,
   which is what actually gets returned by `Model/CustobarApi/ClientBuilder`. The intention with this
   is to make sending of requests and reading response data consistent regardless of the actual client,
   as ZendClient and LaminasClient have some small differences between the method names. This way
   for example `Model/Export/ExportData/Processor/ExecuteExport` doesn't need to deal with the
   client differences.
    1. `Model/CustobarApi/ClientBuilder/VersionClientProvider/ZendClient` will return the actual client
       as `Model/CustobarApi/Client/ZendClient`
    2. `Model/CustobarApi/ClientBuilder/VersionClientProvider/LaminasClient` will return the actual client
       as `Model/CustobarApi/Client/LaminasClient`

Whenever 2.4.6 is agreed to be the oldest supported version for the module, this whole solution can
be dropped and we can directly use `Magento\Framework\HTTP\LaminasClient`.
