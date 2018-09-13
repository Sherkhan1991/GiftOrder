<?php

namespace Appsgenii\Blog\Controller\Post;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Appsgenii\Blog\Model\Post;

class Submit extends Action
{
    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;
    /**
     * @var Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var Post
     */
    protected $post;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    protected $fileSystem;
    protected $uploaderFactory;

    /**
     * @param Context $context
     * @param ResultFactory $resultFactory
     * @param PageFactory $pageFactory
     * @param Post $post
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        PageFactory $pageFactory,
        Post $post,
        \Psr\Log\LoggerInterface $logger,
        Filesystem $fileSystem,
        UploaderFactory $uploaderFactory

    )
    {
        $this->resultFactory = $resultFactory;
        $this->pageFactory = $pageFactory;
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->post = $post;
        $this->logger = $logger;
        return parent::__construct($context);
    }

    /**
     * @param Context     $context
     * @throws LocalizedException
     */
    public function execute()
    {
        $destinationPath = $this->getDestinationPath();
        $files = $this->getRequest()->getFiles('files');

        if ($files) {
            $fileCount = 0;
            foreach($files as $file){
                try {
                    $uploader = $this->uploaderFactory->create(['fileId' => $files[$fileCount]])
                        ->setAllowCreateFolders(true)
                        ->setAllowedExtensions(['jpg', 'jpeg', 'pdf', 'png'])
                        ->setAllowRenameFiles(true);

                    if (!$uploader->save($destinationPath)) {
                        throw new LocalizedException(
                            __('File cannot be saved to path: $1', $destinationPath)
                        );
                    }
                    else{
                        // process the uploaded file
                        $uploadedFiles[] = $uploader->getUploadedFileName();
                        $fileNameString = implode(', ',$uploadedFiles);
                    }
                } catch (\Exception $e) {
                    $this->messageManager->addError(
                        __($e->getMessage())
                    );
                }
                $fileCount++;
            }
            //Save Post to DB
            $title = $_POST['title'];
            $email = $_POST['email'];
            $contact = $_POST['contact'];
            $company = $_POST['company'];
            $message = $_POST['message'];

            $post = $this->post;
            try {
                $post->setTitle($title);
                $post->setEmail($email);
                $post->setContact($contact);
                $post->setCompany($company);
                $post->setMessage($message);
                $post->setFiles($fileNameString);
                $post->save();
                $this->messageManager->addSuccessMessage("Submitted: Thanks for your order");
                $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $redirect->setUrl('/gifts/');
                return $redirect;
            }catch (\Exception $e) {
                //Add a error message if we cant save the new note from some reason
                $this->messageManager->addErrorMessage("Unable to save this Post: " . $title);
                $this->logger->info('Blog Post Submit Error', ['exception' => $e]);
                throw new LocalizedException(__('Blog Post Save Failed'));
            }
        }
    }

    public function getDestinationPath() {

        return $this->fileSystem
            ->getDirectoryWrite(DirectoryList::MEDIA)
            ->getAbsolutePath('/appsgenii/gifts/');
    }


}