import React from 'react';
import Http from '@/http';
import { parseCookies } from '@/helpers/cookies';
import withLayout from '@/hoc/withLayout';

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
      let res = await Http.get('/profile');
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
      <div className="row">
        <div className="col-md-12" style={{ wordBreak: 'break-all'}}>
          {JSON.stringify(this.state.user)}
        </div>
      </div>
    )
  }
}

export default withLayout(Profile);
