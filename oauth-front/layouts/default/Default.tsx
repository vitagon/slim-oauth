import React from 'react';
import Link from 'next/link';
import { Col, Container, Dropdown, DropdownButton, Nav, Navbar, Row } from 'react-bootstrap';
import styles from './Default.module.scss';
import { withRouter } from 'next/router';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import types from '@/store/auth/types';
import Http from '@/http';

class DefaultLayout extends React.Component<any, any> {

    constructor(props) {
        super(props);
        this.state = {
            navExpanded: false
        };
    }

    componentDidMount() {
        document.addEventListener('click', (e) => {
            let target = e.target as HTMLElement;
            if (!target.classList.contains('main-navbar') && !target.closest('.main-navbar')) {
                this.setState({navExpanded: false});
            }
        });
    }

    setNavExpanded = (expanded) => {
        this.setState({navExpanded: expanded});
    };

    closeNav = () => {
        this.setState({navExpanded: false});
    };

    logout = async () => {
        try {
            await Http.post('/api/logout');
        } catch (e) {
            console.error("Failed to logout user");
            console.error(e);
        }

        this.props.removeUser();
        this.props.router.push('/');
    };

    render() {
        return (
            <>
                <Navbar
                    className="main-navbar"
                    bg="dark"
                    expand="lg"
                    variant="dark"
                    onToggle={this.setNavExpanded}
                    expanded={this.state.navExpanded}
                >
                    <Navbar.Toggle aria-controls="basic-navbar-nav"/>
                    <Navbar.Collapse id="basic-navbar-nav">
                        <Nav className="mr-auto">
                            <Link href="/">
                                <a className="nav-link" onClick={this.closeNav}>Home</a>
                            </Link>
                        </Nav>

                        <Nav>
                            {!this.props.user && (
                                <Link href="/login">
                                    <a className="nav-link" onClick={this.closeNav}>Login</a>
                                </Link>
                            )}

                            {this.props.user && (
                                <DropdownButton
                                    menuAlign="right"
                                    title={this.props.user?.name}
                                    id="dropdown-menu-align-right"
                                >
                                    <Link href="/profile">
                                        <a className="dropdown-item" onClick={this.closeNav}>Profile</a>
                                    </Link>
                                    <Dropdown.Item eventKey="2" onClick={this.logout}>Logout</Dropdown.Item>
                                </DropdownButton>
                            )}
                        </Nav>
                    </Navbar.Collapse>
                </Navbar>

                <Container>
                    <Row className={styles.wrap}>
                        <Col>{this.props.children}</Col>
                    </Row>
                </Container>
            </>
        );
    }
}

const mapStateToProps = (state) => ({
   user: state.AuthReducer.user
});

const mapDispatchToProps = (dispatch) => bindActionCreators({
   removeUser: () => (dispatch) => dispatch({ type: types.SET_USER, payload: null })
}, dispatch);

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(DefaultLayout));
