import React from 'react';

class Home extends React.Component<any, any> {

  constructor(props) {
    super(props);
    this.state = {
      user: null
    }
  }

  async componentDidMount() {
    try {
      let res = await fetch('http://auth.company.loc/api/user/self', {
        credentials: 'include'
      });
      let data = await res.json();

      if (!res.ok) {
        let err = new Error(res.statusText);
        // @ts-ignore
        err.response = res;
        throw err;
      }
      this.setState({ user: data });
    } catch (e) {
      console.error(e);
    }
  }

  render() {
    return (
      <div>{JSON.stringify(this.state.user)}</div>
    )
  }
}

export default Home;