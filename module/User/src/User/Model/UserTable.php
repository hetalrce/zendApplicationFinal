<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Session\Container;
use Zend\Db\Sql\Update;

class UserTable extends AbstractTableGateway
{

    protected $tableGateway;
    public $adapter;
    public $table = 'users';

    public function __construct(TableGateway $tableGateway, Adapter $adapter)
    {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->adapter = $adapter;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function saveUser(User $user)
    {
        $data = array(
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => $user->password,
        );
        $this->tableGateway->insert($data);
    }

    public function getUser($email, $password)
    {
        $resultSet = $this->tableGateway->select(['email' => $email, 'password' => $password,]);
        if (!empty($resultSet->current())) {
            return $resultSet->current();
        }
    }

    public function verifyEmailForgotPassword($emailData)
    {
        //echo '<pre>';
        //print_r($this->tableGateway->table);
        // exit;
        $sql = new Sql($this->getAdapter());

        try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()
                    ->from($this->table)
                    ->columns([
                        'id',
                    ])
                    ->where([
                'email' => $emailData['email'],
            ]);
            //  $sqlstring = $sql->buildSqlString($select);
            //  echo $sqlstring;

            $statement = $sql->prepareStatementForSqlObject($select);

            $data = $this->resultSetPrototype->initialize($statement->execute())
                    ->toArray();
            if (count($data)) {
                return $data[0]['id'];
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }

    /**
     * Fetch Users list
     *
     * @access public
     * @param array $where
     *            // Conditions
     * @param array $columns
     *            // Specific column names
     * @param string $orderBy
     *            // Order By conditions
     * @param boolean $paging
     *            // Flag for paging
     * @return array
     */
    public function getUsers($where = array(), $columns = array(), $orderBy = '', $paging = false)
    {

        try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array(
                'users' => $this->table
            ));

            if (count($where) > 0) {
                $select->where($where);
            }

            if (count($columns) > 0) {
                $select->columns($columns);
            }

            if (!empty($orderBy)) {
                $select->order($orderBy);
            }

            $sqlstring = $sql->buildSqlString($select);

            if ($paging) {
                $dbAdapter = new DbSelect($select, $this->getAdapter());
                $paginator = new Paginator($dbAdapter);

                return $paginator;
            } else {
                $statement = $sql->prepareStatementForSqlObject($select);

                $clients = $this->resultSetPrototype->initialize($statement->execute())
                        ->toArray();

                return $clients;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }

    /**
     * Function for changing the User Password
     *
     * @param array $userPasswordData            
     * @return boolean
     */
    function changeUserPassword($userPasswordData)
    {

        $sql = new Sql($this->getAdapter());
        try {
            $new_password = md5($userPasswordData['password']);
            $update = $sql->update();
            $update->table($this->table);

            $data = array(
                'password' => $new_password,
            );

            $update->set($data);

            $update->where([
                'id' => $userPasswordData['userId'],
            ]);
            $statement = $sql->prepareStatementForSqlObject($update);
            $result = $statement->execute();
            // /////Password reset Successfully ///////////
            if ($result->getAffectedRows()) {
                return true;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
        return false;
    }

}
