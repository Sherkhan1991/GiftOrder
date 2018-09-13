<?php

namespace Appsgenii\Blog\Api\Data;

interface PostInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const POST_ID               = 'post_id';
    const TITLE                 = 'title';
    const EMAIL                 = 'email';
    const CONTACT               = 'contact';
    const COMPANY               = 'company';
    const MESSAGE               = 'message';
    const CONTENT               = 'content';
    const FILES               = 'files';
    const CREATED_AT            = 'creation_time';

    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Set Email
     *
     * @param string $email
     * @return $email
     */
    public function setEmail($email);

    /**
     * Set Contact
     *
     * @param string $contact
     * @return $contact
     */
    public function setContact($contact);

    /**
     * Set Company
     *
     * @param string $company
     * @return $company
     */
    public function setCompany($company);

    /**
     * Set Message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * Set Files
     *
     * @param string $files
     * @return $this
     */
    public function setFiles($files);

    /**
     * Set Crated At
     *
     * @param int $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

}