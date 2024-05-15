import { usePage } from '@inertiajs/react';
import React, { useEffect, useState } from 'react'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';


const ChatLayout = ({ children}) => {

    const page = usePage();
    const conversations = page.props.conversations;
    const selectedConversation = page.props.selectedConversation;
    const [localConversations, setLocalConversations] = useState([]);
    const [sortedConversations, setSortedConversations] = useState([]);
    const [onlineUsers, setOnlineUsers] = useState({});

    const isUserOnline = (userId) => onlineUsers[userId] ? true : false;

    console.log('selectedConversation', selectedConversation);
    console.log('conversations', conversations);

    useEffect(() => {
      setSortedConversations(
        localConversations.sort((a, b) => {

          if (a.blocked_at && b.blocked_at) {
            return  a.blocked_at > b.blocked_at ? 1 : -1;
          }else if (a.blocked_at) {
            return 1;
          }else if (b.blocked_at) {
            return -1;
          }

          if (a.last_message_date && b.last_message_date) {
            return  b.last_message_date.localeCompare(
              a.last_message_date
            )
          }else if (a.last_message_date) {
            return -1;
          }else if (b.last_message_date) {
            return 1;
          }else {
            return 0;
          }



        })
      );
    }, [localConversations]);

    useEffect(() => {
      setLocalConversations(conversations);
    }, [conversations]);
 
    useEffect(() => {

      Echo.join('online')
        .here((users) => {

          console.log('here', users);
          const onlineUsersObj = Object.fromEntries(users.map((user) => [user.id, user]));
          setOnlineUsers((prevUsers) => ({ ...prevUsers, ...onlineUsersObj }));

        }).joining((user) => {
          console.log('joining', user);

          setOnlineUsers((prevUsers) => {
            const updatedUsers = { ...prevUsers };
            updatedUsers[user.id] = user;
            return updatedUsers;
          });

        }).leaving((user) => {
          console.log('leaving', user);
          setOnlineUsers((prevUsers) => {
            const updatedUsers = { ...prevUsers };
            delete updatedUsers[user.id];
            return updatedUsers;
          })
        }).error((error) => {
          console.log('error', error);
        });

        return () => {
          Echo.leave('online');
        }


    }, []);

  return (
    <>
        ChatLayout

        <div>{ children }</div>
    </>
  )
}

export default ChatLayout;
