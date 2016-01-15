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
  function setTitle($title);

  /**
   * gets title for a page if any
   *
   * @return string
   * @author 
   **/
  function getTitle();


  /**
   * sets the source property
   *
   * @return void
   * @param string $src
   **/
  function setSource($src);

  /**
   * gets source for a page if any
   *
   * @return string
   * @author 
   **/
  function getSource();
} // END interface PageDocument