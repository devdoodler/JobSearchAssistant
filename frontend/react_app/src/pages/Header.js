import React from 'react';
import { Link } from 'react-router';
import { Navbar, Nav, Container } from 'react-bootstrap';
import { ReactComponent as Logo } from './../assets/jsa-logo.svg';

function Header() {
    return (
        <>
            <Navbar expand="lg" fixed="top" className="bg-white shadow-sm" style={{ padding: '0px 20px' }}>
                <Container className="d-flex justify-content-between align-items-center">
                    <Nav className="mr-auto">
                        <Link to="/">
                            <Logo width="250" height="100" />
                        </Link>
                    </Nav>

                    <Nav className="d-flex flex-grow-1 justify-content-center align-items-start">
                        <Nav.Link as={Link} to="/job-application">
                            Add New Application
                        </Nav.Link>
                        <Nav.Link as={Link} to="/job-application/list">
                            Show All Applications
                        </Nav.Link>
                        <Nav.Link href="#search" style={{ display: 'none' }}>
                            <i className="fas fa-search"></i> Search
                        </Nav.Link>
                    </Nav>
                </Container>
            </Navbar>
        </>
    );
}

export default Header;
