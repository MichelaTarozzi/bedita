<?php
/**
 * BEdita, API-first content management framework
 * Copyright 2017 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */
namespace BEdita\Core\Shell;

use Cake\Console\Shell;

/**
 * Endpoint permissions shell commands:
 *
 * - create: add a new endpoint
 * - ls: list existing endpoints
 * - rm: remove an existing endpoint
 *
 * @since 4.0.0
 */
class EndpointPermissionsShell extends Shell
{

    /**
     * {@inheritDoc}
     */
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('create', [
            'help' => 'create a new endpoint permission',
            'parser' => [
                'description' => [
                    'Create a new endpoint permission.',
                    'First argument (required) indicates endpoint permission\'s read mask.',
                    'Second argument (optional) indicates endpoint permission\'s write mask.'
                ],
                'arguments' => [
                    'read' => ['help' => 'Read mask', 'required' => true],
                    'write' => ['help' => 'Write mask', 'required' => true]
                ],
                'options' => [
                    'application' => ['help' => 'Application name|id', 'required' => false],
                    'endpoint' => ['help' => 'Endpoint name|id', 'required' => false],
                    'role' => ['help' => 'Role name|id', 'required' => false]
                ]
            ]
        ]);
        $parser->addSubcommand('ls', [
            'help' => 'list existing endpoint permissions',
            'parser' => [
                'description' => [
                    'List endpoint permissions.',
                    'Option --application (optional) provides listing by application\'s name|id.',
                    'Option --endpoint (optional) provides listing by endpoint\'s name|id.',
                    'Option --role (optional) provides listing by role\'s name|id.',
                ],
                'options' => [
                    'application' => ['help' => 'Application name|id', 'required' => false],
                    'endpoint' => ['help' => 'Endpoint name|id', 'required' => false],
                    'role' => ['help' => 'Role name|id', 'required' => false]
                ]
            ]
        ]);
        $parser->addSubcommand('rm', [
            'help' => 'remove an existing endpoint permission',
            'parser' => [
                'description' => [
                    'Remove an endpoint permission.',
                    'Option --application (optional) provides listing by application\'s name|id.',
                    'Option --endpoint (optional) provides listing by endpoint\'s name|id.',
                    'Option --role (optional) provides listing by role\'s name|id.',
                ],
                'options' => [
                    'application' => ['help' => 'Application name|id', 'required' => false],
                    'endpoint' => ['help' => 'Endpoint name|id', 'required' => false],
                    'role' => ['help' => 'Role name|id', 'required' => false]
                ]
            ]
        ]);

        return $parser;
    }

    /**
     * create a new endpoint permission
     *
     * @return void
     */
    public function create()
    {
        $this->out('usage: bin/cake endpoint_permissions create <read> <write> [--application=<name|id>] [--endpoint=<name|id>] [--role=<name|id>]');
        $this->out('... coming soon');
    }

    /**
     * list existing endpoint permissions
     *
     * @return void
     */
    public function ls()
    {
        $this->out('usage: bin/cake endpoint_permissions ls [--application=<name|id>] [--endpoint=<name|id>] [--role=<name|id>]');
        $this->out('... coming soon');
    }

    /**
     * remove an existing endpoint permission
     *
     * @return void
     */
    public function rm()
    {
        $this->out('usage: bin/cake endpoint_permissions rm [--application=<name|id>] [--endpoint=<name|id>] [--role=<name|id>]');
        $this->out('... coming soon');
    }
}
