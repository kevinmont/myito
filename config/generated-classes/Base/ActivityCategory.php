<?php

namespace Base;

use \Activity as ChildActivity;
use \ActivityCategory as ChildActivityCategory;
use \ActivityCategoryQuery as ChildActivityCategoryQuery;
use \ActivityQuery as ChildActivityQuery;
use \Question as ChildQuestion;
use \QuestionQuery as ChildQuestionQuery;
use \UserActivityCategory as ChildUserActivityCategory;
use \UserActivityCategoryQuery as ChildUserActivityCategoryQuery;
use \Exception;
use \PDO;
use Map\ActivityCategoryTableMap;
use Map\ActivityTableMap;
use Map\QuestionTableMap;
use Map\UserActivityCategoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'categoria_actividad' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class ActivityCategory implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\ActivityCategoryTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the categoria_actividad_id field.
     *
     * @var        int
     */
    protected $categoria_actividad_id;

    /**
     * The value for the nombre_actividad field.
     *
     * @var        string
     */
    protected $nombre_actividad;

    /**
     * @var        ObjectCollection|ChildActivity[] Collection to store aggregation of ChildActivity objects.
     */
    protected $collActivities;
    protected $collActivitiesPartial;

    /**
     * @var        ObjectCollection|ChildUserActivityCategory[] Collection to store aggregation of ChildUserActivityCategory objects.
     */
    protected $collUserActivityCategories;
    protected $collUserActivityCategoriesPartial;

    /**
     * @var        ObjectCollection|ChildQuestion[] Collection to store aggregation of ChildQuestion objects.
     */
    protected $collQuestions;
    protected $collQuestionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildActivity[]
     */
    protected $activitiesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserActivityCategory[]
     */
    protected $userActivityCategoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildQuestion[]
     */
    protected $questionsScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\ActivityCategory object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>ActivityCategory</code> instance.  If
     * <code>obj</code> is an instance of <code>ActivityCategory</code>, delegates to
     * <code>equals(ActivityCategory)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|ActivityCategory The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [categoria_actividad_id] column value.
     *
     * @return int
     */
    public function getCategoriaActividadId()
    {
        return $this->categoria_actividad_id;
    }

    /**
     * Get the [nombre_actividad] column value.
     *
     * @return string
     */
    public function getNombreActividad()
    {
        return $this->nombre_actividad;
    }

    /**
     * Set the value of [categoria_actividad_id] column.
     *
     * @param int $v new value
     * @return $this|\ActivityCategory The current object (for fluent API support)
     */
    public function setCategoriaActividadId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->categoria_actividad_id !== $v) {
            $this->categoria_actividad_id = $v;
            $this->modifiedColumns[ActivityCategoryTableMap::COL_CATEGORIA_ACTIVIDAD_ID] = true;
        }

        return $this;
    } // setCategoriaActividadId()

    /**
     * Set the value of [nombre_actividad] column.
     *
     * @param string $v new value
     * @return $this|\ActivityCategory The current object (for fluent API support)
     */
    public function setNombreActividad($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nombre_actividad !== $v) {
            $this->nombre_actividad = $v;
            $this->modifiedColumns[ActivityCategoryTableMap::COL_NOMBRE_ACTIVIDAD] = true;
        }

        return $this;
    } // setNombreActividad()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ActivityCategoryTableMap::translateFieldName('CategoriaActividadId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->categoria_actividad_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ActivityCategoryTableMap::translateFieldName('NombreActividad', TableMap::TYPE_PHPNAME, $indexType)];
            $this->nombre_actividad = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = ActivityCategoryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\ActivityCategory'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ActivityCategoryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildActivityCategoryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collActivities = null;

            $this->collUserActivityCategories = null;

            $this->collQuestions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see ActivityCategory::setDeleted()
     * @see ActivityCategory::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityCategoryTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildActivityCategoryQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityCategoryTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ActivityCategoryTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->activitiesScheduledForDeletion !== null) {
                if (!$this->activitiesScheduledForDeletion->isEmpty()) {
                    \ActivityQuery::create()
                        ->filterByPrimaryKeys($this->activitiesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->activitiesScheduledForDeletion = null;
                }
            }

            if ($this->collActivities !== null) {
                foreach ($this->collActivities as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userActivityCategoriesScheduledForDeletion !== null) {
                if (!$this->userActivityCategoriesScheduledForDeletion->isEmpty()) {
                    \UserActivityCategoryQuery::create()
                        ->filterByPrimaryKeys($this->userActivityCategoriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userActivityCategoriesScheduledForDeletion = null;
                }
            }

            if ($this->collUserActivityCategories !== null) {
                foreach ($this->collUserActivityCategories as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->questionsScheduledForDeletion !== null) {
                if (!$this->questionsScheduledForDeletion->isEmpty()) {
                    \QuestionQuery::create()
                        ->filterByPrimaryKeys($this->questionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->questionsScheduledForDeletion = null;
                }
            }

            if ($this->collQuestions !== null) {
                foreach ($this->collQuestions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[ActivityCategoryTableMap::COL_CATEGORIA_ACTIVIDAD_ID] = true;
        if (null !== $this->categoria_actividad_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ActivityCategoryTableMap::COL_CATEGORIA_ACTIVIDAD_ID . ')');
        }
        if (null === $this->categoria_actividad_id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('categoria_actividad_categoria_actividad_id_seq')");
                $this->categoria_actividad_id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ActivityCategoryTableMap::COL_CATEGORIA_ACTIVIDAD_ID)) {
            $modifiedColumns[':p' . $index++]  = 'categoria_actividad_id';
        }
        if ($this->isColumnModified(ActivityCategoryTableMap::COL_NOMBRE_ACTIVIDAD)) {
            $modifiedColumns[':p' . $index++]  = 'nombre_actividad';
        }

        $sql = sprintf(
            'INSERT INTO categoria_actividad (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'categoria_actividad_id':
                        $stmt->bindValue($identifier, $this->categoria_actividad_id, PDO::PARAM_INT);
                        break;
                    case 'nombre_actividad':
                        $stmt->bindValue($identifier, $this->nombre_actividad, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ActivityCategoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getCategoriaActividadId();
                break;
            case 1:
                return $this->getNombreActividad();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['ActivityCategory'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ActivityCategory'][$this->hashCode()] = true;
        $keys = ActivityCategoryTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getCategoriaActividadId(),
            $keys[1] => $this->getNombreActividad(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collActivities) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'activities';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'actividadess';
                        break;
                    default:
                        $key = 'Activities';
                }

                $result[$key] = $this->collActivities->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserActivityCategories) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userActivityCategories';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'usuario_categoria_actividads';
                        break;
                    default:
                        $key = 'UserActivityCategories';
                }

                $result[$key] = $this->collUserActivityCategories->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collQuestions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'questions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'preguntass';
                        break;
                    default:
                        $key = 'Questions';
                }

                $result[$key] = $this->collQuestions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\ActivityCategory
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ActivityCategoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\ActivityCategory
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setCategoriaActividadId($value);
                break;
            case 1:
                $this->setNombreActividad($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = ActivityCategoryTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setCategoriaActividadId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setNombreActividad($arr[$keys[1]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\ActivityCategory The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ActivityCategoryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ActivityCategoryTableMap::COL_CATEGORIA_ACTIVIDAD_ID)) {
            $criteria->add(ActivityCategoryTableMap::COL_CATEGORIA_ACTIVIDAD_ID, $this->categoria_actividad_id);
        }
        if ($this->isColumnModified(ActivityCategoryTableMap::COL_NOMBRE_ACTIVIDAD)) {
            $criteria->add(ActivityCategoryTableMap::COL_NOMBRE_ACTIVIDAD, $this->nombre_actividad);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildActivityCategoryQuery::create();
        $criteria->add(ActivityCategoryTableMap::COL_CATEGORIA_ACTIVIDAD_ID, $this->categoria_actividad_id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getCategoriaActividadId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getCategoriaActividadId();
    }

    /**
     * Generic method to set the primary key (categoria_actividad_id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setCategoriaActividadId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getCategoriaActividadId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \ActivityCategory (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNombreActividad($this->getNombreActividad());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getActivities() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addActivity($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserActivityCategories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserActivityCategory($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getQuestions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addQuestion($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setCategoriaActividadId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \ActivityCategory Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Activity' == $relationName) {
            $this->initActivities();
            return;
        }
        if ('UserActivityCategory' == $relationName) {
            $this->initUserActivityCategories();
            return;
        }
        if ('Question' == $relationName) {
            $this->initQuestions();
            return;
        }
    }

    /**
     * Clears out the collActivities collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActivities()
     */
    public function clearActivities()
    {
        $this->collActivities = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collActivities collection loaded partially.
     */
    public function resetPartialActivities($v = true)
    {
        $this->collActivitiesPartial = $v;
    }

    /**
     * Initializes the collActivities collection.
     *
     * By default this just sets the collActivities collection to an empty array (like clearcollActivities());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initActivities($overrideExisting = true)
    {
        if (null !== $this->collActivities && !$overrideExisting) {
            return;
        }

        $collectionClassName = ActivityTableMap::getTableMap()->getCollectionClassName();

        $this->collActivities = new $collectionClassName;
        $this->collActivities->setModel('\Activity');
    }

    /**
     * Gets an array of ChildActivity objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildActivityCategory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildActivity[] List of ChildActivity objects
     * @throws PropelException
     */
    public function getActivities(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActivitiesPartial && !$this->isNew();
        if (null === $this->collActivities || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collActivities) {
                // return empty collection
                $this->initActivities();
            } else {
                $collActivities = ChildActivityQuery::create(null, $criteria)
                    ->filterByActivityCategory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collActivitiesPartial && count($collActivities)) {
                        $this->initActivities(false);

                        foreach ($collActivities as $obj) {
                            if (false == $this->collActivities->contains($obj)) {
                                $this->collActivities->append($obj);
                            }
                        }

                        $this->collActivitiesPartial = true;
                    }

                    return $collActivities;
                }

                if ($partial && $this->collActivities) {
                    foreach ($this->collActivities as $obj) {
                        if ($obj->isNew()) {
                            $collActivities[] = $obj;
                        }
                    }
                }

                $this->collActivities = $collActivities;
                $this->collActivitiesPartial = false;
            }
        }

        return $this->collActivities;
    }

    /**
     * Sets a collection of ChildActivity objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $activities A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildActivityCategory The current object (for fluent API support)
     */
    public function setActivities(Collection $activities, ConnectionInterface $con = null)
    {
        /** @var ChildActivity[] $activitiesToDelete */
        $activitiesToDelete = $this->getActivities(new Criteria(), $con)->diff($activities);


        $this->activitiesScheduledForDeletion = $activitiesToDelete;

        foreach ($activitiesToDelete as $activityRemoved) {
            $activityRemoved->setActivityCategory(null);
        }

        $this->collActivities = null;
        foreach ($activities as $activity) {
            $this->addActivity($activity);
        }

        $this->collActivities = $activities;
        $this->collActivitiesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Activity objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Activity objects.
     * @throws PropelException
     */
    public function countActivities(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActivitiesPartial && !$this->isNew();
        if (null === $this->collActivities || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActivities) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getActivities());
            }

            $query = ChildActivityQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByActivityCategory($this)
                ->count($con);
        }

        return count($this->collActivities);
    }

    /**
     * Method called to associate a ChildActivity object to this object
     * through the ChildActivity foreign key attribute.
     *
     * @param  ChildActivity $l ChildActivity
     * @return $this|\ActivityCategory The current object (for fluent API support)
     */
    public function addActivity(ChildActivity $l)
    {
        if ($this->collActivities === null) {
            $this->initActivities();
            $this->collActivitiesPartial = true;
        }

        if (!$this->collActivities->contains($l)) {
            $this->doAddActivity($l);

            if ($this->activitiesScheduledForDeletion and $this->activitiesScheduledForDeletion->contains($l)) {
                $this->activitiesScheduledForDeletion->remove($this->activitiesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildActivity $activity The ChildActivity object to add.
     */
    protected function doAddActivity(ChildActivity $activity)
    {
        $this->collActivities[]= $activity;
        $activity->setActivityCategory($this);
    }

    /**
     * @param  ChildActivity $activity The ChildActivity object to remove.
     * @return $this|ChildActivityCategory The current object (for fluent API support)
     */
    public function removeActivity(ChildActivity $activity)
    {
        if ($this->getActivities()->contains($activity)) {
            $pos = $this->collActivities->search($activity);
            $this->collActivities->remove($pos);
            if (null === $this->activitiesScheduledForDeletion) {
                $this->activitiesScheduledForDeletion = clone $this->collActivities;
                $this->activitiesScheduledForDeletion->clear();
            }
            $this->activitiesScheduledForDeletion[]= clone $activity;
            $activity->setActivityCategory(null);
        }

        return $this;
    }

    /**
     * Clears out the collUserActivityCategories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserActivityCategories()
     */
    public function clearUserActivityCategories()
    {
        $this->collUserActivityCategories = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserActivityCategories collection loaded partially.
     */
    public function resetPartialUserActivityCategories($v = true)
    {
        $this->collUserActivityCategoriesPartial = $v;
    }

    /**
     * Initializes the collUserActivityCategories collection.
     *
     * By default this just sets the collUserActivityCategories collection to an empty array (like clearcollUserActivityCategories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserActivityCategories($overrideExisting = true)
    {
        if (null !== $this->collUserActivityCategories && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserActivityCategoryTableMap::getTableMap()->getCollectionClassName();

        $this->collUserActivityCategories = new $collectionClassName;
        $this->collUserActivityCategories->setModel('\UserActivityCategory');
    }

    /**
     * Gets an array of ChildUserActivityCategory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildActivityCategory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserActivityCategory[] List of ChildUserActivityCategory objects
     * @throws PropelException
     */
    public function getUserActivityCategories(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserActivityCategoriesPartial && !$this->isNew();
        if (null === $this->collUserActivityCategories || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserActivityCategories) {
                // return empty collection
                $this->initUserActivityCategories();
            } else {
                $collUserActivityCategories = ChildUserActivityCategoryQuery::create(null, $criteria)
                    ->filterByActivityCategory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserActivityCategoriesPartial && count($collUserActivityCategories)) {
                        $this->initUserActivityCategories(false);

                        foreach ($collUserActivityCategories as $obj) {
                            if (false == $this->collUserActivityCategories->contains($obj)) {
                                $this->collUserActivityCategories->append($obj);
                            }
                        }

                        $this->collUserActivityCategoriesPartial = true;
                    }

                    return $collUserActivityCategories;
                }

                if ($partial && $this->collUserActivityCategories) {
                    foreach ($this->collUserActivityCategories as $obj) {
                        if ($obj->isNew()) {
                            $collUserActivityCategories[] = $obj;
                        }
                    }
                }

                $this->collUserActivityCategories = $collUserActivityCategories;
                $this->collUserActivityCategoriesPartial = false;
            }
        }

        return $this->collUserActivityCategories;
    }

    /**
     * Sets a collection of ChildUserActivityCategory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userActivityCategories A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildActivityCategory The current object (for fluent API support)
     */
    public function setUserActivityCategories(Collection $userActivityCategories, ConnectionInterface $con = null)
    {
        /** @var ChildUserActivityCategory[] $userActivityCategoriesToDelete */
        $userActivityCategoriesToDelete = $this->getUserActivityCategories(new Criteria(), $con)->diff($userActivityCategories);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userActivityCategoriesScheduledForDeletion = clone $userActivityCategoriesToDelete;

        foreach ($userActivityCategoriesToDelete as $userActivityCategoryRemoved) {
            $userActivityCategoryRemoved->setActivityCategory(null);
        }

        $this->collUserActivityCategories = null;
        foreach ($userActivityCategories as $userActivityCategory) {
            $this->addUserActivityCategory($userActivityCategory);
        }

        $this->collUserActivityCategories = $userActivityCategories;
        $this->collUserActivityCategoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserActivityCategory objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserActivityCategory objects.
     * @throws PropelException
     */
    public function countUserActivityCategories(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserActivityCategoriesPartial && !$this->isNew();
        if (null === $this->collUserActivityCategories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserActivityCategories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserActivityCategories());
            }

            $query = ChildUserActivityCategoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByActivityCategory($this)
                ->count($con);
        }

        return count($this->collUserActivityCategories);
    }

    /**
     * Method called to associate a ChildUserActivityCategory object to this object
     * through the ChildUserActivityCategory foreign key attribute.
     *
     * @param  ChildUserActivityCategory $l ChildUserActivityCategory
     * @return $this|\ActivityCategory The current object (for fluent API support)
     */
    public function addUserActivityCategory(ChildUserActivityCategory $l)
    {
        if ($this->collUserActivityCategories === null) {
            $this->initUserActivityCategories();
            $this->collUserActivityCategoriesPartial = true;
        }

        if (!$this->collUserActivityCategories->contains($l)) {
            $this->doAddUserActivityCategory($l);

            if ($this->userActivityCategoriesScheduledForDeletion and $this->userActivityCategoriesScheduledForDeletion->contains($l)) {
                $this->userActivityCategoriesScheduledForDeletion->remove($this->userActivityCategoriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserActivityCategory $userActivityCategory The ChildUserActivityCategory object to add.
     */
    protected function doAddUserActivityCategory(ChildUserActivityCategory $userActivityCategory)
    {
        $this->collUserActivityCategories[]= $userActivityCategory;
        $userActivityCategory->setActivityCategory($this);
    }

    /**
     * @param  ChildUserActivityCategory $userActivityCategory The ChildUserActivityCategory object to remove.
     * @return $this|ChildActivityCategory The current object (for fluent API support)
     */
    public function removeUserActivityCategory(ChildUserActivityCategory $userActivityCategory)
    {
        if ($this->getUserActivityCategories()->contains($userActivityCategory)) {
            $pos = $this->collUserActivityCategories->search($userActivityCategory);
            $this->collUserActivityCategories->remove($pos);
            if (null === $this->userActivityCategoriesScheduledForDeletion) {
                $this->userActivityCategoriesScheduledForDeletion = clone $this->collUserActivityCategories;
                $this->userActivityCategoriesScheduledForDeletion->clear();
            }
            $this->userActivityCategoriesScheduledForDeletion[]= clone $userActivityCategory;
            $userActivityCategory->setActivityCategory(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ActivityCategory is new, it will return
     * an empty collection; or if this ActivityCategory has previously
     * been saved, it will retrieve related UserActivityCategories from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ActivityCategory.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserActivityCategory[] List of ChildUserActivityCategory objects
     */
    public function getUserActivityCategoriesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserActivityCategoryQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUserActivityCategories($query, $con);
    }

    /**
     * Clears out the collQuestions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addQuestions()
     */
    public function clearQuestions()
    {
        $this->collQuestions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collQuestions collection loaded partially.
     */
    public function resetPartialQuestions($v = true)
    {
        $this->collQuestionsPartial = $v;
    }

    /**
     * Initializes the collQuestions collection.
     *
     * By default this just sets the collQuestions collection to an empty array (like clearcollQuestions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initQuestions($overrideExisting = true)
    {
        if (null !== $this->collQuestions && !$overrideExisting) {
            return;
        }

        $collectionClassName = QuestionTableMap::getTableMap()->getCollectionClassName();

        $this->collQuestions = new $collectionClassName;
        $this->collQuestions->setModel('\Question');
    }

    /**
     * Gets an array of ChildQuestion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildActivityCategory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildQuestion[] List of ChildQuestion objects
     * @throws PropelException
     */
    public function getQuestions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collQuestionsPartial && !$this->isNew();
        if (null === $this->collQuestions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collQuestions) {
                // return empty collection
                $this->initQuestions();
            } else {
                $collQuestions = ChildQuestionQuery::create(null, $criteria)
                    ->filterByActivityCategory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collQuestionsPartial && count($collQuestions)) {
                        $this->initQuestions(false);

                        foreach ($collQuestions as $obj) {
                            if (false == $this->collQuestions->contains($obj)) {
                                $this->collQuestions->append($obj);
                            }
                        }

                        $this->collQuestionsPartial = true;
                    }

                    return $collQuestions;
                }

                if ($partial && $this->collQuestions) {
                    foreach ($this->collQuestions as $obj) {
                        if ($obj->isNew()) {
                            $collQuestions[] = $obj;
                        }
                    }
                }

                $this->collQuestions = $collQuestions;
                $this->collQuestionsPartial = false;
            }
        }

        return $this->collQuestions;
    }

    /**
     * Sets a collection of ChildQuestion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $questions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildActivityCategory The current object (for fluent API support)
     */
    public function setQuestions(Collection $questions, ConnectionInterface $con = null)
    {
        /** @var ChildQuestion[] $questionsToDelete */
        $questionsToDelete = $this->getQuestions(new Criteria(), $con)->diff($questions);


        $this->questionsScheduledForDeletion = $questionsToDelete;

        foreach ($questionsToDelete as $questionRemoved) {
            $questionRemoved->setActivityCategory(null);
        }

        $this->collQuestions = null;
        foreach ($questions as $question) {
            $this->addQuestion($question);
        }

        $this->collQuestions = $questions;
        $this->collQuestionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Question objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Question objects.
     * @throws PropelException
     */
    public function countQuestions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collQuestionsPartial && !$this->isNew();
        if (null === $this->collQuestions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collQuestions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getQuestions());
            }

            $query = ChildQuestionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByActivityCategory($this)
                ->count($con);
        }

        return count($this->collQuestions);
    }

    /**
     * Method called to associate a ChildQuestion object to this object
     * through the ChildQuestion foreign key attribute.
     *
     * @param  ChildQuestion $l ChildQuestion
     * @return $this|\ActivityCategory The current object (for fluent API support)
     */
    public function addQuestion(ChildQuestion $l)
    {
        if ($this->collQuestions === null) {
            $this->initQuestions();
            $this->collQuestionsPartial = true;
        }

        if (!$this->collQuestions->contains($l)) {
            $this->doAddQuestion($l);

            if ($this->questionsScheduledForDeletion and $this->questionsScheduledForDeletion->contains($l)) {
                $this->questionsScheduledForDeletion->remove($this->questionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildQuestion $question The ChildQuestion object to add.
     */
    protected function doAddQuestion(ChildQuestion $question)
    {
        $this->collQuestions[]= $question;
        $question->setActivityCategory($this);
    }

    /**
     * @param  ChildQuestion $question The ChildQuestion object to remove.
     * @return $this|ChildActivityCategory The current object (for fluent API support)
     */
    public function removeQuestion(ChildQuestion $question)
    {
        if ($this->getQuestions()->contains($question)) {
            $pos = $this->collQuestions->search($question);
            $this->collQuestions->remove($pos);
            if (null === $this->questionsScheduledForDeletion) {
                $this->questionsScheduledForDeletion = clone $this->collQuestions;
                $this->questionsScheduledForDeletion->clear();
            }
            $this->questionsScheduledForDeletion[]= clone $question;
            $question->setActivityCategory(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->categoria_actividad_id = null;
        $this->nombre_actividad = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collActivities) {
                foreach ($this->collActivities as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserActivityCategories) {
                foreach ($this->collUserActivityCategories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collQuestions) {
                foreach ($this->collQuestions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collActivities = null;
        $this->collUserActivityCategories = null;
        $this->collQuestions = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ActivityCategoryTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
