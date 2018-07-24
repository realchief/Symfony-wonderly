<?php

namespace FrontendBundle\Services;

use FrontendBundle\Entity\Post;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class GetBlog
 * @package FrontendBundle\Services
 *
 */
class GetBlog
{

    /**
     * URL Blog.
     */
    private $blogUrl;

    /**
     * Create Connection with mail service
     *
     * @param ContainerInterface $container A ContainerInterface instance.
     *
     */
    public function __construct(ContainerInterface $container)
    {
        $this->blogUrl = $container->getParameter('wonder_blog_url') . '/wp-json/wp/v2/posts';
    }

    /**
     * Get Last Posts
     *
     * @param integer $count Count posts.
     *
     * @return mixed
     */
    public function getLastPosts($count)
    {
        $request_params = array(
            'per_page' => $count,
        );

        $url = $this->blogUrl . '?' . http_build_query($request_params);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $result = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $result = json_decode($result, true);

        if (!is_array($result) || ($statusCode > 299)) {
            return 'Blog not found';
        }

        $result = array_map(array($this, 'getArrayPosts'), $result);

        return $result;
    }

    /**
     * Get Array Posts
     *
     * @param array $data Array post.
     *
     * @return mixed
     */
    private function getArrayPosts(array $data)
    {
        $url = $this->blogUrl . '/' . $data['id'] . '?_embed=true';
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $postData = curl_exec($curl);
        curl_close($curl);

        $postData = json_decode($postData, true);
        $post = 'This post is not defined';

        if (is_array($postData)) {
            $post = new Post($postData);
        }

        return $post;
    }
}
