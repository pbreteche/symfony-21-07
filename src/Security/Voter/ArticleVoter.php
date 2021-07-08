<?php

namespace App\Security\Voter;

use App\Entity\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['ARTICLE_EDIT', 'ARTICLE_DELETE'])
            && $subject instanceof Article;
    }

    /**
     * @param Article $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'ARTICLE_EDIT':
            case 'ARTICLE_DELETE':
                try {
                    return $user === $subject->getWrittenBy()->getAuthenticatedBy();
                } catch (\Throwable $exception) {
                    return false;
                }
        }

        return false;
    }
}
