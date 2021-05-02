<?php

class Item
{

    /**
    * @var int ID пользователя
    */

    private int $id;

    /**
    * @var string имя пользователя
    */

    private string $name;

    /**
    * @var int статус пользователя
    */

    private int $status;

    /**
    * @var bool статус изменения пользователя
    */
    private bool $changed;

     /**
     * Конструктор
     * @param int $id <p>ID пользователя</p>
     * @return void <p>Инициализация пользователя</p>
     */

    public function __construct($id)
    {
        $this->id = $id;
        $this->init();
    }

     /**
     * Поиск пользователя в базе данных
     * <p>Запись результата в свойства name и status</p>
     */

    private function init() {

        // Соединение с БД
        $db = Db::getConnection();

        $query = 'SELECT name, status FROM objects WHERE id = :id';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->name = $row->name;
            $this->status = $row->status;            
        } 

    }

    /**
     * Доступ к свойству
     * @param string $property <p>Имя свойства</p>
     * @return mixed <p>Возвращает значение свойства</p>
     * @throws Exception <p>Ошибка, если свойство нет</p>
     */

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            throw new Exception('No existing property.');
        }
    }

     /**
     * Задание свойств объекта
     * @param string $property <p>Имя свойства</p>
     * @param mixed $value <p>Значение свойства</p>
     * @throws Exception <p>Ошибка, если свойство нет или не верный тип</p>
     */

    public function __set($property, $value) {
        if (property_exists($this, $property) and isset($value)) {

            if('string' == gettype($value) and 'name' == $property) {
                $this->name = $value;
           } elseif ('integer' == gettype($value) and 'status' == $property) {
                $this->status = $value;
           } elseif ('boolean' == gettype($value) and 'changed' == $property) {
                $this->changed = $value;
           } else {
                throw new Exception('Invalid property type.');
           }

           $this->save();

        } else {
            throw new Exception('No existing property.');
        }
    }

     /**
     * Сохраняет установленные значения name и status в случае, если свойства объекта были изменены извне
     */

    public function save() {
        
        // Соединение с БД
        $db = Db::getConnection();

        $query = 'INSERT INTO objects (name, status) VALUES (:name, :status)';

        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':status', $this->status, PDO::PARAM_INT);
        $stmt->execute();
    }

}
