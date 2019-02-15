<?php
namespace StageBit\Blog\Api\Data;

interface AllblogsInterface
{
    const POST_ID = 'post_id';
    const TITLE  = 'name';
    const EXCERPT = 'post_excerpt';
    const DESCRIPTION = 'post_content';
    const AUTHOR_NAME = 'author_name';
    const AUTHOR_WEBSITE = 'author_website';
    const FEATURED_IMAGE = 'featured_image';
    const STATUS = 'is_active';
    const DISPLAY_DATE = 'display_date';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const URL_KEY = 'url_key';

    public function getId();

    public function getName();

    public function getPostExcerpt();

    public function getPostContent();

    public function getAuthorName();

    public function getAuthorWebsite();

    public function getFeaturedImage();

    public function getIsActive();

    public function getDisplayDate();

    public function getCreatedAt();

    public function getUpdatedAt();

    public function getUrlKey();

//    ==================================================================

    public function setId($id);

    public function setName($title);

    public function setPostExcerpt($post_excerpt);

    public function setPostContent($post_content);

    public function setAuthorName($author_name);

    public function setAuthorWebsite($author_website);

    public function setFeaturedImage($featured_image);

    public function setIsActive($status);

    public function setDisplayDate($display_date);

    public function setCreatedAt($created_at);

    public function setUpdatedAt($updated_at);

    public function setUrlKey($url_key);
}
?>