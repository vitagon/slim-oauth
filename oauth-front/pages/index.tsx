import Head from 'next/head';
import styles from '../styles/Home.module.css';
import { wrapper } from '@/store';
import withLayout from '@/hoc/withLayout';
import DefaultLayout from '@/layouts/default/Default';
import Http from '@/http';

const Home = (props) => {
    return (
        <DefaultLayout user={props.user}>
            <div className="row">
                <div className="col-md-12">
                    {props.message}
                </div>
            </div>
        </DefaultLayout>
    )
}

export async function getServerSideProps(ctx) {
    let data = null;
    try {
        let res = await Http.get('/profile', {
            headers: {
                'Authorization': 'Bearer ' + ctx.req.cookies['X-Auth']
            }
        });
        data = res.data;
    } catch (e) {
        console.log(e);
    }

    return {
        props: {
            message: 'gg',
            user: data ? data.user : null
        }
    }
}

export default Home;
