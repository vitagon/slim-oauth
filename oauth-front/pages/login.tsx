import React from 'react';
import styles from '@/styles/Login.module.scss';
import classNames from 'classnames';
import Http from '@/http';
import { withRouter } from 'next/router';
import FullPage from '@/layouts/full-page/FullPage';
import { getUser } from '@/services/AuthService';

interface State {
    form: {
        login: string,
        password: string,
    },
    errors: {
        login: string,
        password: string,
    }
}

class Login extends React.Component<any, State> {

    constructor(props) {
        super(props);
        this.state = {
            form: {
                login: '',
                password: ''
            },
            errors: {
                login: '',
                password: ''
            }
        };
    }

    login = async () => {
        let data = null;
        try {
            let res = await Http.post('/api/login', this.state.form);
            data = res.data;
        } catch (e) {
            console.error(e);
            throw e;
        }

        this.props.router.push('/profile');
    };

    changeFormVal = (e) => {
        let name = e.target.name;
        let value = e.target.value;

        this.setState({
            ...this.state,
            form: Object.assign({}, this.state.form, {[name]: value})
        });
    };

    render() {
        return (
            <FullPage>
                <div className={styles.loginRow}>
                    <div className="py-4 px-4">
                        <a href="/" className={styles.logo}>
                            <span>Company</span>
                        </a>

                        <p className={styles.signInText}>Вход в аккаунт</p>

                        <form method="POST" action="/login">
                            <div className="mb-3">
                                <label htmlFor="" className="form-label">Email или телефон</label>
                                <input
                                    type="text"
                                    className={classNames(
                                        'form-control', this.state.errors.login ? 'is-invalid' : ''
                                    )}
                                    name="login"
                                    value={this.state.form.login}
                                    onChange={this.changeFormVal}
                                    required
                                    autoComplete="login"
                                    autoFocus
                                />

                                {this.state.errors.login && (
                                    <span className="invalid-feedback" role="alert">
                                        <strong>{this.state.errors.login}</strong>
                                    </span>
                                )}
                            </div>

                            <div className="mb-3">
                                <label htmlFor="" className="form-label">Пароль</label>
                                <a href="/forget-password" className={styles.forgetPwdLink} tabIndex={-1}>Забыли
                                    пароль?</a>
                                <input
                                    type="password"
                                    className={classNames(
                                        'form-control', this.state.errors.password ? 'is-invalid' : ''
                                    )}
                                    name="password"
                                    value={this.state.form.password}
                                    onChange={this.changeFormVal}
                                    required
                                    autoComplete="current-password"
                                    autoFocus
                                />

                                {this.state.errors.password && (
                                    <span className="invalid-feedback" role="alert">
                    <strong>{this.state.errors.password}</strong>
                </span>
                                )}
                            </div>

                            <div className="mt-8">
                                <button
                                    type="button"
                                    className="btn btn-block btn-primary"
                                    onClick={this.login}
                                >
                                    Войти
                                </button>
                            </div>
                        </form>
                    </div>

                    <div className={styles.bottomBlock}>
                        <span className="text-gray-600 text-sm">Нет аккаунта? </span>

                        <a href="/register" className="mx-2">
                            Создать
                        </a>
                    </div>
                </div>
            </FullPage>
        );
    }
}

export async function getServerSideProps(ctx) {
    let user = await getUser(ctx.req.headers.cookie);

    if (user) {
        let {res} = ctx;
        res.setHeader('Location', '/profile');
        res.statusCode = 302;
        return;
    }

    return {
        props: {}
    };
}

export default withRouter(Login);
