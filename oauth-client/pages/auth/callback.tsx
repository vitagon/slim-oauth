import React from 'react';
import { Spinner } from 'react-bootstrap';
import { withRouter } from 'next/router';
import Http from '@/http';

const wrapStyles = {
    width: '100vw',
    height: '100vh',
}

const spinnerStyles = {
    width: '4em',
    height: '4em',
    border: '.4em solid currentColor',
    borderRightColor: 'transparent',
}

class Callback extends React.Component<any, any> {

    async componentDidMount() {
        if (!this.props.router.query.code) {
            alert('Required param code was not found!');
            return;
        }

        let router = this.props.router;
        try {
            await Http.post('/auth/token', { code: router.query.code });
        } catch (e) {
            alert('Invalid code.');
            window.location.href = router.query.cur_url;
        }

        if (router.query.cur_url && !router.query.cur_url.startsWith('/')) {
            window.location.href = router.query.cur_url;
        }

        window.location.href = '/';
    }

    render() {
        return (
            <div className="d-flex justify-content-center align-items-center" style={wrapStyles}>
                <Spinner animation="border" variant="primary" style={spinnerStyles}/>
            </div>
        );
    }
}

export const getServerSideProps = async (ctx) => {
    return ({
        props: {
            layout: 'fullPage',
        }
    })
}

export default withRouter(Callback);