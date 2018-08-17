<?php

namespace Appsgenii\Blog\Controller\Post;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Exception\LocalizedException;
//use Magento\Framework\Controller\Result\JsonFactory;
//use Magefan\Blog\Api\DataManagementInterface;
use Appsgenii\Blog\Model\PostFactory;

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
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->resultFactory = $resultFactory;
        $this->postFactory = $postFactory;
        $this->logger = $logger;
        $this->pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    /**
     * @param Context     $context
     * @throws LocalizedException
     */
    public function execute()
    {
        $input = print_r($_POST);
        $bgImage = $this->getRequest()->getFiles('files');
        print_r($bgImage);

        var_dump($_POST['message']);
        exit();
        $title = $_POST['title'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $company = $_POST['company'];
        $message = $_POST['message'];
       echo $title;
       echo $email;
       echo $contact;
       echo $company;
       echo $content;

        $data = array(
            'title'=>$title,
            'email'=>$email,
            'contact'=>$contact,
            'company'=>$company,
            'message'=>$message,
            'files'=>$files
        );
        $postResource = $this->postFactory->create();
        try {
            $postResource->setData($data);
            $postResource->save();
            $this->messageManager->addSuccessMessage("New Post: " . $title . " Created");
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