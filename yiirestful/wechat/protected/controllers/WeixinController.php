// <?php	
// /** 
// 2.	* actionIndex 
// 3.	* 
// 4.	* @access public 
// 5.	* @return void 
// 6.	*/ 

// 	public function actionIndex() 
// 	{ 
// 	$weixin = new Weixin($_GET); 
// 	$weixin->token = $this->_weixinToken; 
// 	//$weixin->debug = true; 
	
// 	//��ַ����ʱʹ�� 
// 	if (isset($_GET['echostr'])) 
// 	{ 
// 	$weixin->valid(); 
// 	} 

// 	$weixin->init(); 
// 	$reply = ''; 
// 	$msgType = empty($weixin->msg->MsgType) ? '' : strtolower($weixin->msg->MsgType); 
// 	switch ($msgType) 
// 	{ 
// 	case 'text': 
// 	//��Ҫ�����ı���Ϣ���� 
// 	break; 
// 	case 'image': 
// 	//��Ҫ����ͼ����Ϣ���� 
// 	break; 
// 	case 'location': 
// 	//��Ҫ����λ����Ϣ���� 
// 	break; 
// 	case 'link': 
// 	//��Ҫ����������Ϣ���� 
// 	break; 
// 	case 'event': 
// 			//��Ҫ�����¼���Ϣ���� 
// 	break; 
// 	default: 
// 	//��Ч��Ϣ����µĴ���ʽ 
// 	break; 
// 	} 
// 	$weixin->reply($reply); 
// 	} 
// ?>