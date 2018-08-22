<?php

namespace Appsgenii\Blog\Controller\Post;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Exception\LocalizedException;
use Appsgenii\Blog\Model\PostFactory;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

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
     * @var PostFactory
     */
    protected $postFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    protected $fileSystem;
    protected $uploaderFactory;

    /**
     * @param Context $context
     * @param ResultFactory $resultFactory
     * @param ManagementInterfaceFactory $postFactory
     * @param ManagementInterface $postRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        PageFactory $pageFactory,
        PostFactory $postFactory,
        \Psr\Log\LoggerInterface $logger,
        Filesystem $fileSystem,
        UploaderFactory $uploaderFactory

    )
    {
        $this->resultFactory = $resultFactory;
        $this->postFactory = $postFactory;
        $this->logger = $logger;
        $this->pageFactory = $pageFactory;
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
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
                        var_dump($uploadedFiles);
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

            $data = array(
                'title'=>$title,
                'email'=>$email,
                'contact'=>$contact,
                'company'=>$company,
                'message'=>$message,
                'files'=>$fileNameString

            );
            $postResource = $this->postFactory->create();
            try {
                $postResource->setData($data);
                $postResource->save();
                $this->messageManager->addSuccessMessage("Submitted: Thanks your order submitted");
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