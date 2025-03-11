import React from 'react';
import { Link } from 'react-router';
import { Navbar, Nav, Container } from 'react-bootstrap';
import { ReactComponent as Logo } from './../assets/jsa-logo.svg';

function Header() {
    return (
        <>
            <Navbar expand="lg" fixed="top">
                <Container>
                    <Nav className="ml-auto d-flex justify-content-center w-100">
                        <Nav.Link as={Link} to="/job-application">
                            Add New Application
                        </Nav.Link>
                        <Nav.Link as={Link} to="/">
                            Show All Applications
                        </Nav.Link>
                        <Nav.Link href="#search">
                            <i className="fas fa-search disabled"></i> Search
                        </Nav.Link>
                    </Nav>
                </Container>
            </Navbar>
            <div className="logo-container text-center mt-0 pt-4">
                <Logo width="250" height="100" />
            </div>
        </>
    );
}

export default Header;
