<?php

namespace FrontendBundle\Entity;

/**
 * Class Post
 *
 * @package FrontEndBundle\Entity
 *
 */
class Post
{

    /**
     * idPost.
     */
    private $idPost;

    /**
     * idPost.
     */
    private $postUrl;

    /**
     * title.
     */
    private $title;

    /**
     * createdDate.
     */
    private $createdDate;

    /**
     * imageURL.
     */
    private $imageUrl;

    /**
     * Content.
     */
    private $content;

//    /**
//     * Content.
//     */
//    private $category;

    /**
     * Get construct.
     *
     * @param array $post
     */
    public function __construct(array $post)
    {
        $this->setIdPost($post['id']);
        if (!empty($post['title']['rendered'])) {
            $this->setTitle($post['title']['rendered']);
        }
        $this->setCreateDate(new \DateTime($post['date_gmt']));
        if (!empty($post['featured_media'])) {
            $this->setImageUrl($post['_embedded']['wp:featuredmedia'][0]['source_url']);
        }
        if (!empty($post['title']['rendered'])) {
            $this->setContent($post['content']['rendered']);
        }
        $this->setPostUrl($post['link']);
    }

    /**
     * Get idPost.
     *
     * @return integer
     */
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * Set idPost.
     *
     * @param integer $idPost A Post ID.
     *
     * @return Post
     */
    public function setIdPost($idPost)
    {
        $this->idPost = $idPost;
        return $this;
    }

    /**
     * Get Title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Title.
     *
     * @param string $title A title.
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get createdDate.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createdDate;
    }

    /**
     * Set Title.
     *
     * @param \DateTime $createdDate A Created Date.
     *
     * @return Post
     */
    public function setCreateDate(\DateTime $createdDate)
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * Get Image URL.
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set Image URL.
     *
     * @param string $imageUrl A Image URL.
     *
     * @return Post
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * Get Content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set Content.
     *
     * @param string $content A Content.
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get Post URl.
     *
     * @return string
     */
    public function getPostUrl()
    {
        return $this->postUrl;
    }

    /**
     * Set Post URL.
     *
     * @param string $url A Content.
     *
     * @return Post
     */
    public function setPostUrl($url)
    {
        $this->postUrl = $url;
        return $this;
    }

//    /**
//     * Get Category.
//     *
//     * @return array
//     */
//    public function getCategory()
//    {
//        return $this->content;
//    }
//
//    /**
//     * Set Category.
//     *
//     * @param array $categories A Category.
//     *
//     * @return Post
//     */
//    public function setCategory($categories)
//    {
//        $new_categories = array();
//        $i = 0;
//        foreach ($categories as $category){
//            $new_categories[$i]['name'] = $category['name'];
//            $new_categories[$i]['link'] = $category['link'];
//            $i++;
//        }
//        $this->category = $new_categories;
//        return $this;
//    }
}
