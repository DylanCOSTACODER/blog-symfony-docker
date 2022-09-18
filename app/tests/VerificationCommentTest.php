<?php

namespace App\Tests;

use App\Entity\Comment;
use App\Service\VerificationComment;
use PHPUnit\Framework\TestCase;

class VerificationCommentTest extends TestCase
{

    protected $comment;

    protected function setUp(): void
    {
        $this->comment = new Comment();
    }

    public function testContientMotInterdit()
    {
        $service = new VerificationComment();

        $this->comment->setContenu("Ceci est un mauvais commentaire");

        $result = $service->commentNonAuthorise($this->comment);
        $this->assertTrue($result);
    }

    public function testContientPasMotInterdit()
    {
        $service = new VerificationComment();

        $this->comment->setContenu("Ceci est un bon commentaire");

        $result = $service->commentNonAuthorise($this->comment);
        $this->assertFalse($result);
    }
}
