import { usePage } from '@inertiajs/react';
import React, { useEffect } from 'react'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';


const ChatLayout = ({ children}) => {

    const page = usePage();
    const conversations = page.props.conversations;
    const selectedConversation = page.props.selectedConversation;

    // console.log('selectedConversation', selectedConversation);
    // console.log('conversations', conversations);
 
    useEffect(() => {

      Echo.join('online')
        .here((users) => {
          console.log('here', users);
        }).joining((user) => {
          console.log('joining', user);
        }).leaving((user) => {
          console.log('leaving', user);
        }).error((error) => {
          console.log('error', error);
        });
    }, []);

  return (
    <>
        ChatLayout

        <div>{ children }</div>
    </>
  )
}

export default ChatLayout;
