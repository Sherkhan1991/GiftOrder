<?php

namespace Appsgenii\Blog\Controller\Post;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\JsonFactory;
//use Magefan\Blog\Api\DataManagementInterface;
use Appsgenii\Blog\Model\PostFactory;

class Submit extends Action
{
    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
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
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

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
        PageFactory $resultPageFactory,
        PostFactory $postFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        $this->resultFactory = $resultFactory;
        $this->postFactory = $postFactory;
        $this->logger = $logger;
        $this->ResultFactory = $resultFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }

    /**
     * @return $this|\Magento\Framework\Controller\Result\Json
     * @throws LocalizedException
     */
    public function execute()
    {
        //$input = file_get_contents('php://input');
        $title = $_POST['title'];
        $content = $_POST['email'];
        $content = $_POST['contact'];
        $content = $_POST['company'];
        $content = $_POST['content'];
       echo $title;
       echo $content;

        exit();
    }
}