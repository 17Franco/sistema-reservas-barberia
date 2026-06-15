import { Component, ChangeDetectorRef, inject } from '@angular/core';
import { RouterOutlet, RouterLink, Router } from '@angular/router';
import { NgFor, NgIf } from '@angular/common';
import { NavBar } from "../../components/nav-bar/nav-bar";
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-home',
  imports: [RouterOutlet, RouterLink, NgFor, NgIf, NavBar],
  templateUrl: './home.html',
  styleUrl: './home.scss',
})
export class Home {

  
}
