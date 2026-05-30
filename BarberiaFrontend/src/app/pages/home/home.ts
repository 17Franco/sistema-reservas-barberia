import { Component } from '@angular/core';
import { Router, RouterOutlet } from '@angular/router';
import { NavBar } from "../../components/nav-bar/nav-bar";

@Component({
  selector: 'app-home',
  imports: [RouterOutlet, NavBar],
  templateUrl: './home.html',
  styleUrl: './home.scss',
})
export class Home {



}
