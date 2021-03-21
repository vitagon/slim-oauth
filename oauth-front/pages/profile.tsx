import React from 'react';
import Http from '@/http';
import DefaultLayout from "@/layouts/default/Default";

class Profile extends React.Component<any, any> {

    constructor(props) {
        super(props);
    }

    async componentDidMount() {
    }

    render() {
        return (
            <DefaultLayout user={this.props.user}>
                <div className="row">
                    <div className="col-md-12" style={{wordBreak: 'break-all'}}>
                        {JSON.stringify(this.props.user)}
                    </div>
                </div>
            </DefaultLayout>
        )
    }
}

export async function getServerSideProps(ctx) {
    let data = null;
    try {
        let res = await Http.get('/profile', {
            headers: { cookie: ctx.req.headers.cookie }
        });
        data = res.data;
    } catch (e) {
        console.error(e);
        ctx.res.setHeader('Location', '/');
        ctx.res.statusCode = 302;
        return;
    }

    return {
        props: {
            user: data ? data.user : null
        }
    }
}

export default Profile;
