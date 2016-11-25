<?php

namespace pvr\EzCommentBundle\Security;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ConfigBuilderInterface;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Security\PolicyProvider\PolicyProviderInterface;

class CommentProvider implements PolicyProviderInterface
{

    /**
     * Adds policies configuration hash to $configBuilder.
     *
     * Policies configuration hash contains declared modules, functions and limitations.
     * First level key is the module name, value is a hash of available functions, with function name as key.
     * Function value is an array of available limitations, identified by the alias declared in LimitationType service tag.
     * If no limitation is provided, value can be null.
     *
     * @return array
     */
    public function addPolicies(ConfigBuilderInterface $configBuilder)
    {
        $configBuilder->addConfig([
            "comment" => [
                "add" => null,
                "edit" => null,
                "delete" => null,
            ],
        ]);
    }
}