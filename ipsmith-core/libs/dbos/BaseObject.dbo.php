<?php
/**
 * BaseClass for all database objects.
 * will be extended by other classes.
 *
 * @author Rainer Bendig <rbe@ipsmith.org>
 * @version 1.0
 * @since 0.0.1
 */
class BaseObject
{
     private $connection = null;

     function __construct()
     {
          global $doctrineConnection;
          $this->connection = $doctrineConnection;
     }

     function Escape($data)
     {

          return $data;
          $returndata = array();

          foreach ($data as $k => $v)
          {

               $returndata[$k] =  $this->connection->quote($v);
          }

          return $returndata;
     }

     function Store($table,$data)
     {

          $data = $this->Escape($data);

          if(isset($this->id) && $this->id>0)
          {
               $this->connection->update($table,$data, array('id' => $this->id )                                                                                                                                                                                               );
          }
          else
          {
               $this->connection->insert($table,$data);
          }
     }

     function Remove($table)
     {
          if(isset($this->id) && $this->id>0)
          {
               $this->connection->delete($table,array('id' => $this->id ));
          }
     }

     function GetBy($table,$querydata)
     {

          $q = 'SELECT * FROM '.$table.' WHERE ';
          $values = array();
          $i = 0;

          foreach ($querydata as $k => $v)
          {
               $values[] = $v;

               if($i>0)
               {
                    $q.=" AND ";
               }
               $q.= $k.' = ? ';

               $i=$i+1;
          }

          return $this->connection->fetchAssoc($q,$values);
     }


     public function GetAll($table, $querydata)
     {

          $q = 'SELECT * FROM '.$table.' WHERE ';
          $values = array();
          $i = 0;

          foreach ($querydata as $k => $v)
          {
               $values[] = $v;

               if($i>0)
               {
                    $q.=" AND ";
               }
               $q.= $k.' = ? ';

               $i=$i+1;
          }

          return $this->connection->fetchAll($q,$values);
     }
