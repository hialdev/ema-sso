<?php
/**
 * Copyright 2015 Eric Enold <zyberspace@zyberware.org>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * php-client for telegram-cli.
 * If you don't need the command-wrappers in this class or want to make your own, use the RawClient-class. :)
 */
class TelegramClient
{
    /**
     * The file handler for the socket-connection
     *
     * @var ressource
     */
    protected $_fp;

    /**
     * If telegram-cli returns an error, the error-message gets stored here.
     *
     * @var string
     */
    protected $_errorMessage = null;

    /**
     * If telegram-cli returns an error, the error-code gets stored here.
     *
     * @var int
     */
    protected $_errorCode = null;

    /**
     * Connects to the telegram-cli.
     *
     * @param string $remoteSocket Address of the socket to connect to. See stream_socket_client() for more info.
     *                             Can be 'unix://' or 'tcp://'.
     *
     * @throws Exception Throws an exception if no connection can be established.
     */
    public function __construct($remoteSocket)
    {
        $this->_fp = stream_socket_client($remoteSocket);
        if ($this->_fp === false) {
            throw new Exception('Could not connect to socket "' . $remoteSocket . '"');
        }
    }

    /**
     * Closes the connection to the telegram-cli.
     */
    public function __destruct()
    {
        fclose($this->_fp);
    }

    /**
     * Executes a command on the telegram-cli. Line-breaks will be escaped, as telgram-cli does not support them.
     *
     * @param string $command The command. Command-arguments can be passed as additional method-arguments.
     *
     * @return object|boolean Returns the answer as a json-object or true on success, false if there was an error.
     */
    public function exec($command)
    {
        $command = implode(' ', func_get_args());

        fwrite($this->_fp, str_replace("\n", '\n', $command) . PHP_EOL);

        $answer = fgets($this->_fp); //"ANSWER $bytes" or false if an error occurred
        if (is_string($answer)) {
            if (substr($answer, 0, 7) === 'ANSWER ') {
                $bytes = ((int) substr($answer, 7)) + 1; //+1 because the json-return seems to miss one byte
                if ($bytes > 0) {
                    $bytesRead = 0;
                    $jsonString = '';

                    //Run fread() till we have all the bytes we want
                    //(as fread() can only read a maximum of 8192 bytes from a read-buffered stream at once)
                    do {
                        $jsonString .= fread($this->_fp, $bytes - $bytesRead);
                        $bytesRead = strlen($jsonString);
                    } while ($bytesRead < $bytes);

                    $json = json_decode($jsonString);

                    if (!isset($json->error)) {
                        //Reset error-message and error-code
                        $this->_errorMessage = null;
                        $this->_errorCode = null;

                        //For "status_online" and "status_offline"
                        if (isset($json->result) && $json->result === 'SUCCESS') {
                            return true;
                        }

                        //Return json-object
                        return $json;
                    } else {
                        $this->_errorMessage = $json->error;
                        $this->_errorCode = $json->error_code;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Returns the error-message retrieved vom telegram-cli, if there is one.
     *
     * @return string|null The error-message retrieved from telegram-cli or null if there was no error.
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * Returns the error-code retrieved vom telegram-cli, if there is one.
     *
     * @return string|null The error-message retrieved from telegram-cli or null if there was no error.
     */
    public function getErrorCode()
    {
        return $this->_errorCode;
    }

    /**
     * Escapes strings for usage as command-argument.
     * T"es't -> "T\"es\'t"
     *
     * @param string $argument The argument to escape
     *
     * @return string The escaped command enclosed by double-quotes
     */
    public function escapeStringArgument($argument)
    {
        return '"' . addslashes($argument) . '"';
    }

    /**
     * Replaces all spaces with underscores.
     *
     * @param string $peer The peer to escape
     *
     * @return string The escaped peer
     */
    public function escapePeer($peer)
    {
        return str_replace(' ', '_', $peer);
    }

    /**
     * Takes a list of peers and turns it into a format needed by the most commands that handle multiple peers.
     * Every single peer gets escaped by escapePeer().
     *
     * @param array $peerList The list of peers that shall get formated
     *
     * @return string The formated list of peers
     *
     * @uses escapePeer()
     */
    public function formatPeerList(array $peerList)
    {
        return implode(' ', array_map(array($this, 'escapePeer'), $peerList));
    }

    /**
     * Turns the given $fileName into an absolute file path and escapes him
     *
     * @param string $fileName The path to the file (can be relative or absolute)
     *
     * @return string The absolute path to the file
     */
    public function formatFileName($fileName)
    {
        return $this->escapeStringArgument(realpath($fileName));
    }

    /**
     * Sets status as online.
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     */
    public function setStatusOnline()
    {
        return $this->exec('status_online');
    }

    /**
     * Sets status as offline.
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     */
    public function setStatusOffline()
    {
        return $this->exec('status_offline');
    }

    /**
     * Sends a typing notification to $peer.
     * Lasts a couple of seconds or till you send a message (whatever happens first).
     *
     * @param string $peer The peer, gets escaped with escapePeer()
     *
     * @return boolean true on success, false otherwise
     */
    public function sendTyping($peer)
    {
        return $this->exec('send_typing ' . $this->escapePeer($peer));
    }

    /**
     * Sends a text message to $peer.
     *
     * @param string $peer The peer, gets escaped with escapePeer()
     * @param string $msg The message to send, gets escaped with escapeStringArgument()
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     * @uses escapePeer()
     * @uses escapeStringArgument()
     */
    public function msg($peer, $msg)
    {
        $peer = $this->escapePeer($peer);
        $msg = $this->escapeStringArgument($msg);
        return $this->exec('msg ' . $peer . ' ' . $msg);
    }

    /**
     * Sends a text message to several users at once.
     *
     * @param array $userList List of users / contacts that shall receive the message,
     *                        gets formated with formatPeerList()
     * @param string $msg The message to send, gets escaped with escapeStringArgument()
     *
     * @return boolean true on success, false otherwise
     */
    public function broadcast(array $userList, $msg)
    {
        return $this->exec('broadcast ' . $this->formatPeerList($userList) . ' '
            . $this->escapeStringArgument($msg));
    }

    /**
     * Creates a new group chat with the users in $userList.
     *
     * @param string $chatTitle The title of the new chat
     * @param array $userList The users you want to add to the chat. Gets formatted with formatPeerList().
     *                        The current telgram-user (who creates the chat) will be added automatically.
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     * @uses escapeStringArgument()
     * @uses formatPeerList()
     */
    public function createGroupChat($chatTitle, $userList)
    {
        if (count($userList) <= 0) {
            return false;
        }

        return $this->exec('create_group_chat', $this->escapeStringArgument($chatTitle),
            $this->formatPeerList($userList));
    }

    /**
     * Returns an info-object about a chat (title, name, members, admin, etc.).
     *
     * @param string $chat The name of the chat (not the title). Gets escaped with escapePeer().
     *
     * @return object|boolean A chat-object; false if somethings goes wrong
     *
     * @uses exec()
     * @uses escapePeer()
     */
    public function chatInfo($chat)
    {
        return $this->exec('chat_info', $this->escapePeer($chat));
    }

    /**
     * Renames a chat. Both, the chat title and the print-name will change.
     *
     * @param string $chat The name of the chat (not the title). Gets escaped with escapePeer().
     * @param string $chatTitle The new title of the chat.
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     * @uses escapePeer()
     * @uses escapeStringArgument()
     */
    public function renameChat($chat, $newChatTitle)
    {
        return $this->exec('rename_chat', $this->escapePeer($chat), $this->escapeStringArgument($newChatTitle));
    }

    /**
     * Adds a user to a chat.
     *
     * @param string $chat The chat you want the user to add to. Gets escaped with escapePeer().
     * @param string $user The user you want to add. Gets escaped with escapePeer().
     * @param int $numberOfMessagesToFoward The number of last messages of the chat, the new user should see.
     *                                      Default is 100.
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     * @uses escapePeer()
     */
    public function chatAddUser($chat, $user, $numberOfMessagesToFoward = 100)
    {
        return $this->exec('chat_add_user', $this->escapePeer($chat), $this->escapePeer($user),
            (int) $numberOfMessagesToFoward);
    }

    /**
     * Deletes a user from a chat.
     *
     * @param string $chat The chat you want the user to delete from. Gets escaped with escapePeer().
     * @param string $user The user you want to delete. Gets escaped with escapePeer().
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     * @uses escapePeer()
     */
    public function chatDeleteUser($chat, $user)
    {
        return $this->exec('chat_del_user', $this->escapePeer($chat), $this->escapePeer($user));
    }

    /**
     * Sets the profile name
     *
     * @param $firstName The first name
     * @param $lastName The last name
     *
     * @return object|boolean Your new user-info as an object; false if somethings goes wrong
     *
     * @uses exec()
     */
    public function setProfileName($firstName, $lastName)
    {
        return $this->exec('set_profile_name ' . $this->escapeStringArgument($firstName) . ' '
            . $this->escapeStringArgument($lastName));
    }

    /**
     * Adds a user to the contact list
     *
     * @param string $phoneNumber The phone-number of the new contact, needs to be a telegram-user.
     *                            Every char that is not a number gets deleted, so you don't need to care about spaces,
     *                            '+' and so on.
     * @param string $firstName The first name of the new contact
     * @param string $lastName The last name of the new contact
     *
     * @return object|boolean The new contact-info as an object; false if somethings goes wrong
     *
     * @uses exec()
     * @uses escapeStringArgument()
     */
    public function addContact($phoneNumber, $firstName, $lastName)
    {
        $phoneNumber = preg_replace('%[^0-9]%', '', (string) $phoneNumber);
        if (empty($phoneNumber)) {
            return false;
        }

        return $this->exec('add_contact ' . $phoneNumber . ' ' . $this->escapeStringArgument($firstName)
            . ' ' . $this->escapeStringArgument($lastName));
    }

    /**
     * Renames a user in the contact list
     *
     * @param string $contact The contact, gets escaped with escapePeer()
     * @param string $firstName The new first name for the contact
     * @param string $lastName The new last name for the contact
     *
     * @return object|boolean The new contact-info as an object; false if somethings goes wrong
     *
     * @uses exec()
     * @uses escapeStringArgument()
     */
    public function renameContact($contact, $firstName, $lastName)
    {
        return $this->exec('rename_contact ' . $this->escapePeer($contact)
            . ' ' . $this->escapeStringArgument($firstName) . ' ' . $this->escapeStringArgument($lastName));
    }

    /**
     * Deletes a contact.
     *
     * @param string $contact The contact, gets escaped with escapePeer()
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     * @uses escapePeer()
     */
    public function deleteContact($contact)
    {
        return $this->exec('del_contact ' . $this->escapePeer($contact));
    }


    /**
     * Blocks a user .
     *
     * @param string $user The user, gets escaped with escapePeer()
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     * @uses escapePeer()
     */
    public function blockUser($user)
    {
        return $this->exec('block_user ' . $this->escapePeer($user));
    }

    /**
     * Unblocks a user.
     *
     * @param string $user The user, gets escaped with escapePeer()
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     * @uses escapePeer()
     */
    public function unblockUser($user)
    {
        return $this->exec('unblock_user ' . $this->escapePeer($user));
    }

    /**
     * Marks all messages with $peer as read.
     *
     * @param string $peer The peer, gets escaped with escapePeer()
     *
     * @return boolean true on success, false otherwise
     *
     * @uses exec()
     * @uses escapePeer()
     */
    public function markRead($peer)
    {
        return $this->exec('mark_read ' . $this->escapePeer($peer));
    }

    /**
     * Returns an array of all contacts. Every contact is an object like it gets returned from `getUserInfo()`.
     *
     * @return array|boolean An array with your contacts as objects; false if somethings goes wrong
     *
     * @uses exec()
     *
     * @see getUserInfo()
     */
    public function getContactList()
    {
        return $this->exec('contact_list');
    }

    /**
     * Returns the informations about the user as an object.
     *
     * @param string $user The user, gets escaped with escapePeer()
     *
     * @return object|boolean An object with informations about the user; false if somethings goes wrong
     *
     * @uses exec()
     * @uses escapePeer()
     */
    public function getUserInfo($user)
    {
        return $this->exec('user_info ' . $this->escapePeer($user));
    }

    /**
     * Returns an array of all your dialogs. Every dialog is an object with type "user" or "chat".
     *
     * @return array|boolean An array with your dialogs; false if somethings goes wrong
     *
     * @uses exec()
     *
     * @see getUserInfo()
     */
    public function getDialogList()
    {
        return $this->exec('dialog_list');
    }

    /**
     * Returns an array of your past message with that $peer. Every message is an object which provides informations
     * about it's type, sender, retriever and so one.
     * All messages will also be marked as read.
     *
     * @param string $peer The peer, gets escaped with escapePeer()
     * @param int $limit (optional) Limit answer to $limit messages. If not set, there is no limit.
     * @param int $offset (optional) Use this with the $limit parameter to go through older messages.
     *                    Can also be negative.
     *
     * @return array|boolean An array with your past messages with that $peer; false if somethings goes wrong
     *
     * @uses exec()
     * @uses escapePeer()
     */
    public function getHistory($peer, $limit = null, $offset = null)
    {
        if ($limit !== null) {
            $limit = (int) $limit;
            if ($limit < 1) { //if limit is lesser than 1, telegram-cli crashes
                $limit = 1;
            }
            $limit = ' ' . $limit;
        } else {
            $limit = '';
        }
        if ($offset !== null) {
            $offset = ' ' . (int) $offset;
        } else {
            $offset = '';
        }

        return $this->exec('history ' . $this->escapePeer($peer) . $limit . $offset);
    }

    /**
     * Send picture to peer
     *
     * @param  string $peer The peer, gets escaped with escapePeer()
     * @param  string $path The picture path, gets formatted with formatFileName()
     * @return boolean
     *
     * @uses exec()
     * @uses escapePeer()
     * @uses formatFileName()
     */
    public function sendPicture($peer, $path)
    {
        $peer = $this->escapePeer($peer);
        $formattedPath = $this->formatFileName($path);

        return $this->exec('send_photo ' . $peer . ' ' . $formattedPath);
    }

    /**
     * Send file to peer
     *
     * @param  string $peer The peer, gets escaped with escapePeer()
     * @param  string $path The file path, gets formatted with formatFileName()
     * @return boolean
     *
     * @uses exec()
     * @uses escapePeer()
     * @uses formatFileName()
     */
    public function sendFile($peer, $path)
    {
        $peer = $this->escapePeer($peer);
        $formattedPath = $this->formatFileName($path);

        return $this->exec('send_file ' . $peer . ' ' . $formattedPath);
    }
}
