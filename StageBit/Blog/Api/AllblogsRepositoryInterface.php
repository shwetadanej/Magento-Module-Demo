<?php
namespace StageBit\Blog\Api;

interface AllblogsRepositoryInterface
{
    public function save(\StageBit\Blog\Api\Data\AllblogsInterface $blogs);

    public function getById($postId);

    public function delete(\StageBit\Blog\Api\Data\AllblogsInterface $blogs);

    public function deleteById($postId);
}
?>