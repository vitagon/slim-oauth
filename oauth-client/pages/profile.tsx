import React from 'react';
import DefaultLayout from "@/layouts/default/Default";
import { wrapper } from '@/store';
import { getUser } from '@/services/AuthService';

class Profile extends React.Component<any, any> {

    constructor(props) {
        super(props);
    }

    async componentDidMount() {
    }

    render() {
        return (
            <DefaultLayout>
                <div className="row">
                    <div className="col-md-12" style={{wordBreak: 'break-all'}}>
                        {JSON.stringify(this.props.user)}
                    </div>
                </div>
            </DefaultLayout>
        )
    }
}

export const getServerSideProps = wrapper.getServerSideProps(
    async ({ store , req, res}) => {
        let user = await getUser(req.headers.cookie);

        if (!user) {
            res.setHeader('Location', '/');
            res.statusCode = 302;
            return;
        }

        store.dispatch({
            type: 'SET_USER',
            payload: user
        })
    }
)

export default Profile;
