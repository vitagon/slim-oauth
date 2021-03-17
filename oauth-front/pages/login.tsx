import React from 'react';
import styles from '@/styles/Login.module.scss';
import classNames from 'classnames';

class Login extends React.Component<any, any> {

  static layout: string;

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
    }
  }

  login = async () => {
    console.log(this.state.form);
    try {
      const reqOptions = {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(this.state.form),
        credentials: 'include'
      }

      // @ts-ignore
      let res = await fetch('http://auth.company.loc/login', reqOptions);
      let data = await res.text();

      if (!res.ok) {
        let err = new Error(res.statusText);
        // @ts-ignore
        err.response = res;
        throw err;
      }
    } catch (e) {
      console.error(e);
    }
  }

  changeFormVal = (e) => {
    let name = e.target.name;
    let value = e.target.value;

    this.setState({
      ...this.state,
      form: Object.assign({}, this.state.form, { [name]: value })
    });
  }

  render() {
    return (
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
                  <strong>{ this.state.errors.login }</strong>
                </span>
              )}
            </div>

            <div className="mb-3">
              <label htmlFor="" className="form-label">Пароль</label>
              <a href="/forget-password" className={styles.forgetPwdLink} tabIndex={-1}>Забыли пароль?</a>
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
                    <strong>{ this.state.errors.password }</strong>
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
    );
  }
}

Login.layout = 'fullPage';

export default Login;