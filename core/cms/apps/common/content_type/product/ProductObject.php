<?php

class ProductObject extends Object
{

	public $price;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Object the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{object}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return CMap::mergeArray(array( array('price', 'required')), Object::extraRules());
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return CMap::mergeArray(array(),Object::extraRelationships());
	}

     /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return CMap::mergeArray(array('price'=> t('site','Price')),Object::extraLabel(			
		));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		return Object::extraSearch($this);
	}
        
    protected function beforeSave() {
		if (parent::beforeSave()) {
			if ($this -> isNewRecord) {
				$this -> object_type = 'product';
				Object::extraBeforeSave('create', $this);

			} else {
				Object::extraBeforeSave('update', $this);

			}
			return true;
		} else
			return false;
	}

	protected function afterSave() {
		parent::afterSave();
		if ($this -> isNewRecord) {
			Object::saveMetaValue('price', $this->price, $this, true);			
		} else {
			Object::saveMetaValue('price', $this->price, $this, false);			
		}
	}
        
    public static function Resources(){    
              return CMap::mergeArray(Object::Resources(),
				array(                      		   	                  
					'video'=>array('type'=>'video',
		              'name'=>'Video',
		              'maxSize'=>UPLOAD_MAX_SIZE, 
		              'minSize'=>UPLOAD_MIN_SIZE,
		              'max'=>1,
		              'allow'=>array('flv',
		                             'mp4',)))
				);

        }
        
        public static function Permissions(){
              return Object::Permissions();
        }
        
        public static function buildLink($obj){						
		if($obj->object_id)
			return SITE_PATH."/post?id=".$obj->object_id."&slug=".$obj->object_slug;
		else 
			return null;
			}
}