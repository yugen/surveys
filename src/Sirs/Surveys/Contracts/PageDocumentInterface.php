<?php

namespace Sirs\Surveys\Contracts;

/**
 * describes public interface for PageDocuments
 *
 * @package Sirs/Surveys
 * @author
 **/
interface PageDocumentInterface extends ContainerInterface
{
    /**
     * sets the title property
     *
     * @return void
     * @param string $title
     **/
    public function setTitle($title);

    /**
     * gets title for a page if any
     *
     * @return string
     * @author
     **/
    public function getTitle();


    /**
     * sets the source property
     *
     * @return void
     * @param string $src
     **/
    public function setSource($src);

    /**
     * gets source for a page if any
     *
     * @return string
     * @author
     **/
    public function getSource();
} // END interface PageDocument
