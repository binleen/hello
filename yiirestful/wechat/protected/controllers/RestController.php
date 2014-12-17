<?php
    class RestController extends Controller
        {
        // Actions
        public function actionList()
        {
            {
                $items = User::model()->findAll();
                if(empty($items))
                {
                    $this->_sendResponse(200, 'No items');
                }
                else
                {
                    $rows = array();
                    foreach($items as $item)
                        $rows[] = $item->attributes;
                    $this->_sendResponse(200, CJSON::encode($rows));

                }
            }
        }
        public function actionView()
        {
            if(!isset($_GET['id']))
                $this->_sendResponse(500, 'Item ID is missing' );
            $item = User::model()->findByPk($_GET['id']);
            if(is_null($item))
                $this->_sendResponse(404, 'No Item found');
            else
                $this->_sendResponse(200, CJSON::encode($item));
        }

        public function actionCreate()
        {
            $item = new User;
            foreach($_POST as $var=>$value)
            {
                if($item->hasAttribute($var))
                    $item->$var = $value;
                else
                    $this->_sendResponse(500, 'Parameter Error');
            }
            if($item->save())
                $this->_sendResponse(200, CJSON::encode($item));
            else
                $this->_sendResponse(500, 'Could not Create Item');
        }

        public function actionUpdate()
        {
            //获取 put 方法所带来的 json 数据
            $json = file_get_contents('php://input');
            $put_vars = CJSON::decode($json,true);

            $item = User::model()->findByPk($_GET['id']);

            if(is_null($item))
                $this->_sendResponse(400, 'No Item found');

            foreach($put_vars as $var=>$value)
            {
                if($item->hasAttribute($var))
                    $item->$var = $value;
                else
                    $this->_sendResponse(500, 'Parameter Error');
            }

            if($item->save())
                $this->_sendResponse(200, CJSON::encode($item));
            else
                $this->_sendResponse(500, 'Could not Update Item');
        }


        public function actionDelete()
        {
            $item = User::model()->findByPk($_GET['id']);
            if(is_null)
                $this->_sendResponse(400, 'No Item found');
            if($item->delete())
                $this->_sendResponse(200, 'Delete Success');
            else
                $this->_sendResponse(500, 'Could not Delete Item');
        }

        public function actionCheckMobile($phone)
        {
            
        }

        // Assistant Functions
        private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
        {
            $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
            header($status_header);
            header('Content-type: ' . $content_type);
            echo $body;
            Yii::app()->end();
        }

        private function _getStatusCodeMessage($status)
        {
            $codes = Array(
                200 => 'OK',
                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
            );
            return (isset($codes[$status])) ? $codes[$status] : '';
        }

    }
?>