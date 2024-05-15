
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ChatLayout from '@/Layouts/ChatLayout';
import { Head } from '@inertiajs/react';

function Home({ auth }) {
    return (
        <>
        Messages
        </>
    );
}

Home.layout = (page) => {

    return (
        <AuthenticatedLayout 
            user={ page.props.auth.user }
            children={ page }
        >
            <ChatLayout children={page} />
        </AuthenticatedLayout>
    );

};

export default Home;
