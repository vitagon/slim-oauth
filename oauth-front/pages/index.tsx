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
    console.log(ctx.req.headers)
    try {
        let res = await Http.get('/profile', {
            headers: { cookie: ctx.req.headers.cookie }
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
