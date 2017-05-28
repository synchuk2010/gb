<?php

use yii\db\Migration;

/**
 * Начальная миграция, создаёт структуру БД
 * */
class m170204_063632_create_initial_structure extends Migration
{
    /**
     * Создаёт таблицы и индексы для БД
     * */
    public function safeUp()
    {
        /*
         * Создаём таблицы
         * */

        // Таблица с записями гостевой книги
        $this->createTable('entries', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull(),
            'email' => $this->string(100)->notNull(),
            'message' => $this->text()->notNull(),
            'created' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'user' => $this->integer(11)->defaultValue(null),
        ]);
        // Таблица с пользователями
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'email' => $this->string(100)->notNull()->unique(),
            'password' => $this->binary(60)->notNull(),
            'name' => $this->string(150)->notNull(),
            'hash' => $this->string(32),
            'theme' => $this->string(10)->notNull()->defaultValue('default'),
            'rows' => $this->integer(11)->defaultValue(10)
        ]);

        /*
         * Добавляем индексы
         * */

        // Добавляем индекс полю с ИД пользователя
        $this->createIndex(
            'idx-entries-user',
            'entries',
            'user'
        );
        // Добавляем внешний ключ на ИД пользователя
        $this->addForeignKey(
            'fk-entries-user',
            'entries',
            'user',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * Откатывает миграцию
     * */
    public function safeDown()
    {
        /*
         * Удаляем индексы
         * */

        // Удаляем внешний ключ
        $this->dropForeignKey(
            'fk-entries-user',
            'entries'
        );
        // Удаляем индекс
        $this->dropIndex(
            'idx-entries-user',
            'entries'
        );

        /*
         * Удаляем таблицы
         * */

        // Удаляем таблицу с пользователями
        $this->dropTable('users');
        // Удаляем таблицу со статьями
        $this->dropTable('entries');
    }

}
