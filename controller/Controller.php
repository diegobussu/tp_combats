<?php

namespace combats\controller;

use Symfony\Component\Asset\PathPackage;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;



abstract class Controller
{
    protected $twig;
    protected $pathView = 'view';
    protected $pathRoot;
    protected $controller;

    protected $vars = [];
    protected $action = false;

    public function __construct( array $params=[] )
    {

        $this->setParams( $params );
        $this->pathRoot = str_replace( $_SERVER['QUERY_STRING'], '', $_SERVER['REDIRECT_URL'] );
        $this->pathView = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . $this->pathView;

        // Twig initialization
        $loader = new FilesystemLoader( $this->pathView );

        $this->twig = new Environment($loader, [
            'debug' => true
        ]);
        // Asset package
        $versionStrategy = new StaticVersionStrategy('v1');
        $defaultPackage = new Package($versionStrategy);
        $namedPackages = [
            'doc'   => new PathPackage( $this->pathRoot . 'public', $versionStrategy )
        ];

        $assetPackage = new Packages( $defaultPackage, $namedPackages );
    //    var_dump( $assetPackage );die;

        $this->twig->addGlobal('pathRoot', $this->pathRoot );
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addExtension(new AssetExtension($assetPackage));

        // Call action
        if( $this->action ) {
            $action = $this->action . 'Action';
            $this->$action();
        } else {
            $this->defaultAction();
        }
    }

    abstract public function defaultAction();

    protected function setParams( array $params=[] )
    {
        if( isset( $params['action'] ) ) {
            $this->action = $params['action'];
        }
        if( isset( $params['vars'] ) && is_array( $params['vars'] ) ) {
            $nb = count($params['vars']);
            if ($nb > 1) {
                $i = 0;
                while ($i < $nb) {
                    $this->vars[$params['vars'][$i]] = $params['vars'][$i + 1];
                    $i += 2;
                }
            }
        }
        if( isset( $params['postvars'] ) ) {
            $this->vars = $params['postvars'];
        }
    }

    protected function render( $view, $data=[] )
    {
        extract( $data );
        $fileNameView = ucfirst( $view ) . 'View.twig';
        $filePath = $this->pathView . '/' . $fileNameView;
        if( file_exists( $filePath ) ) {
            echo $this->twig->render( $fileNameView, $data );
        } else {
            die( "Twig view file not exist in view folder." );
        }
    }

}