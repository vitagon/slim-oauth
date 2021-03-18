import React from 'react';
import Http from '@/http';
import { parseCookies } from '@/helpers/cookies';

class Profile extends React.Component<any, any> {

  constructor(props) {
    super(props);
    this.state = {
      user: null
    }

  }

  async componentDidMount() {
    let data = null;
    try {
      let res = await Http.get('/profile', {
        headers: {
          cookie: document.cookie
        }
      });
      data = res.data;
    } catch (e) {
      console.error(e);
      throw e;
    }
    console.log(data);
    this.setState({ user: data });
  }

  render() {
    return (
      <div>{JSON.stringify(this.state.user)}</div>
    )
  }
}

export default Profile;
