import React from 'react';
import { Link } from 'react-router';
import { Navbar, Nav, Container } from 'react-bootstrap';
import { ReactComponent as Logo } from './../assets/jsa-logo.svg';

function Header() {
    return (
        <>
            <Navbar expand="lg" fixed="top" className="bg-white shadow-sm">
                <Container>

                    <Nav className="ml-auto d-flex flex-grow-1 justify-content-center align-items-start">
                        <Nav.Link as={Link} to="/job-application">
                            Add New Application
                        </Nav.Link>
                        <Nav.Link as={Link} to="/">
                            Show All Applications
                        </Nav.Link>
                        <Nav.Link href="#search" style={{display: 'none'}}>
                            <i className="fas fa-search"></i> Search
                        </Nav.Link>
                    </Nav>
                    <Nav className="d-flex justify-content-center mt-auto pb-2">
                        <Logo width="250" height="100"/>
                    </Nav>
                </Container>
            </Navbar>
        </>
    );
}

export default Header;
