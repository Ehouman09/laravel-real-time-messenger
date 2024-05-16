import React from 'react'
import UseraIcon from '@heroicons/react/24/solid/UserIcon';

const GroupAvatar = ({

}) =>{
  return (
    <>
        <div className={`avatar placeholder`}>
            <div className="bg-gray-400 text-gray-800 rounded-full w-8"> 
                <span className='text-xl'>
                    <UseraIcon className='w-4' />
                </span>
            </div>
        </div>
    </>
  )
}


export default GroupAvatar;
