<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\File\FileService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;

class UploadAvatarService
{
    private UserRepository $userRepository;
    private FileService $fileService;
    private string $mediaPath;

    public function __construct(UserRepository $userRepository, FileService $fileService, string $mediaPath)
    {

        $this->userRepository = $userRepository;
        $this->fileService = $fileService;
        $this->mediaPath = $mediaPath;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function uploadAvatar(Request $request, User $user): User
    {
        $file = $this->fileService->validateFile($request, FileService::AVATAR_INPUT_NAME);

        $this->fileService->deleteFile($user->getAvatar());

        $fileName = $this->fileService->uploadFile($file, FileService::AVATAR_INPUT_NAME);

        $user->setAvatar($fileName);

        $this->userRepository->save($user);

        return $user;
    }
}