<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\ValueObject;

final readonly class Comment
{
    private const int COMMENT_MAX_LENGTH = 1500;

    public function __construct(private ?string $comment)
    {
    }

    public static function create(?string $comment):self {
        $comment = new self($comment);
        $comment->guard();

        return $comment;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    private function guard(): void
    {
        $commentLength = mb_strlen($this->comment);
        if ($commentLength > self::COMMENT_MAX_LENGTH) {
            throw new InvalidComment();
        }

    }
}
