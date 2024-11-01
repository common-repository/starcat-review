<?php return array(
    'root' => array(
        'pretty_version' => 'dev-develop',
        'version' => 'dev-develop',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'reference' => '099a34f6e943784b61801d62193e3619b0b64fc9',
        'name' => '__root__',
        'dev' => false,
    ),
    'versions' => array(
        '__root__' => array(
            'pretty_version' => 'dev-develop',
            'version' => 'dev-develop',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'reference' => '099a34f6e943784b61801d62193e3619b0b64fc9',
            'dev_requirement' => false,
        ),
        'composer/installers' => array(
            'pretty_version' => 'v1.12.0',
            'version' => '1.12.0.0',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/./installers',
            'aliases' => array(),
            'reference' => 'd20a64ed3c94748397ff5973488761b22f6d3f19',
            'dev_requirement' => false,
        ),
        'pauple/pluginator' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'type' => 'pauple-library',
            'install_path' => __DIR__ . '/../pauple/pluginator',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'reference' => '934b81636b00827dbc40b18bcfe7a215fc6ff7c1',
            'dev_requirement' => false,
        ),
        'roundcube/plugin-installer' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'shama/baton' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
    ),
);
