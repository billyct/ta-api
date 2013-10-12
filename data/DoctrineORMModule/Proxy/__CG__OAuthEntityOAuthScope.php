<?php

namespace DoctrineORMModule\Proxy\__CG__\OAuth\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class OAuthScope extends \OAuth\Entity\OAuthScope implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function getScope()
    {
        $this->__load();
        return parent::getScope();
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function getDescription()
    {
        $this->__load();
        return parent::getDescription();
    }

    public function setId($id)
    {
        $this->__load();
        return parent::setId($id);
    }

    public function setScope($scope)
    {
        $this->__load();
        return parent::setScope($scope);
    }

    public function setName($name)
    {
        $this->__load();
        return parent::setName($name);
    }

    public function setDescription($description)
    {
        $this->__load();
        return parent::setDescription($description);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'scope', 'name', 'description');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}