<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Categories
        $categories = [];
        $catData = [
            ['Web Development', '#007bff'],
            ['PHP', '#e74c3c'],
            ['JavaScript', '#f39c12']
        ];

        foreach ($catData as $data) {
            $category = new Category();
            $category->setName($data[0]);
            // Assume Category has color field; ignore if not
            $manager->persist($category);
            $categories[] = $category;
        }
        $manager->flush();

        // Posts
        $postData = [
            [
                'title' => 'Introduction to Symfony 7',
                'content' => 'Symfony 7 brings exciting new features like improved DX, better performance... Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'cats' => [$categories[0], $categories[1]]
            ],
            [
                'title' => 'Modern JavaScript Frameworks',
                'content' => 'Exploring React, Vue and Svelte in 2024. Each framework has its strengths... Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'cats' => [$categories[2]]
            ],
            [
                'title' => 'Docker for PHP Developers',
                'content' => 'Containerize your PHP apps easily with Docker. Best practices for multi-stage builds... Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
                'cats' => [$categories[1]]
            ]
        ];

        $posts = [];
        foreach ($postData as $data) {
            $post = new Post();
            $post->setTitle($data['title']);
            $post->setContent($data['content']);
            $post->setCreatedAt(new \DateTime());
            foreach ($data['cats'] as $cat) {
                $post->addCategory($cat);
            }
            $manager->persist($post);
            $posts[] = $post;
        }

        // Comments
        $commentData = [
            ['John Doe', 'Great article! Very helpful for beginners.', $posts[0]],
            ['Jane Smith', 'Thanks for sharing this. Will try Symfony 7 soon!', $posts[0]],
            ['Dev Guru', 'Nice overview of JS frameworks.', $posts[1]]
        ];

        foreach ($commentData as $data) {
            $comment = new Comment();
            $comment->setUsername($data[0]);
            $comment->setContent($data[1]);
            $comment->setPost($data[2]);
            $manager->persist($comment);
        }

        $manager->flush();
    }
}

