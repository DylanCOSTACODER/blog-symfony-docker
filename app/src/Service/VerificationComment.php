<?php


namespace App\Service;

use App\Entity\Comment;

class VerificationComment
{
    public function commentNonAuthorise(Comment $comment)
    {
        $nonAuthorise = [
            "mauvais",
            "merde",
            "pourri"
        ];

        foreach ($nonAuthorise as $nonAuthorise) {
            if (strpos($comment->getContenu(), $nonAuthorise)) {
                return true;
            }
        }

        return false;
    }
}
